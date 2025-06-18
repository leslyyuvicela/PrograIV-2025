<?php

namespace App\Http\Controllers;


use App\Models\Campaign;
use App\Models\Stage;
use App\Models\monetary_stage;
use App\Models\goods_stage;
use App\Models\services_stage;
use Illuminate\Http\Request;
use App\Http\Requests\CreateCampaignRequest;
use App\Services\FirebaseStorageService;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class CampaignController extends Controller


{
    protected $firebase;

    public function __construct(FirebaseStorageService $firebase)
    {
       
        $this->firebase = $firebase;
    }

    /*
    FundraisingPreview {
    id: string;
    title: string;
    imageUrl: string;
    progress: number;
    moneyRaised?: number; // Ya que las recaudaciones pueden tener entre uno y tres tipos de contribuciones, cuando solo se pida un tipo o dos, los otros serán nulos.
    goodsRaised?: number;
    goodsType?: string; // Solo si es de tipo "goods"
    servicesRaised?: number;
}
    */ 

public function recommendedFundraisings()
{
    $fundraisings = Campaign::with([
        'images',
        'stages.monetary_stage',
        'stages.goods_stage',
        'stages.services_stage',
    ])
    ->where('status', 1)
    ->orderBy('created_at', 'desc')
    ->take(8)
    ->get()
    ->map(function ($fundraising) {
        $firstImage = $fundraising->images->first();
        $imageUrl = $firstImage ? $firstImage->url : null;

       $progress = 0;
$moneyRaised = 0;
$goodsRaised = 0;
$servicesRaised = 0;
$deadline = null;

foreach ($fundraising->stages as $stage) {
    if ($stage->type == 'money') {
        $moneyRaised = 1;
    } 
    elseif ($stage->type == 'goods') {
        $goodsRaised = 1;
    } 
    elseif ($stage->type == 'services') {
        $servicesRaised = 1;
    }

    if ($deadline === null ||
        ($stage->deadline && $stage->deadline > $deadline)
    ) {
        $deadline = $stage->deadline;
    }
}

return [
    'id' => $fundraising->id,
    'title' => $fundraising->title,
    'imageUrl' => $imageUrl,
    'progress' => $progress,
    'moneyRaised' => $moneyRaised,
    'goodsRaised' => $goodsRaised,
    'servicesRaised' => $servicesRaised,
    'deadline' => $deadline,
];
    });
    return response()->json($fundraisings, 200);
}
   
public function store(CreateCampaignRequest $request)

    {
        
        // Validación de los campos de la campaña
        $validated = $request->validated();

        // Crear campaña
        /*$campaign = Campaign::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'repeat' => $request->repeat ?? false,
        ]);
        */

        DB::beginTransaction();
        try {
            
        $campaign = Campaign::create([
            'user_id' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category_id' => $validated['category'],
            'status' => true

        ]);


        // Crear etapas
        foreach ($validated['stages'] as $stageData) {
            $stage = Stage::create([
                'campaign_id' => $campaign->id,
                'title' => $stageData['title'],
                'description' => $stageData['description'],
                'deadline' => Carbon::parse($stageData['deadline']),
                'type' => $stageData['type'],
            ]);

            switch ($stageData['type']) {
                case 1: // Monetaria
                    monetary_stage::create([
                        'stage_id' => $stage->id,
                        //cantidad de dinero con decimales
                        'money_goal' => (float) $stageData['amount'],
                    ]);
                    break;

                case 2: // Bienes
                    goods_stage::create([
                        'stage_id' => $stage->id,
                        'goods_goal' => (int) $stageData['amount'],
                        'needed_goods' => $stageData['needed_goods'],
                        'address' => $stageData['address'],
                    ]);
                    break;

                case 3: // Servicios
                    services_stage::create([
                        'stage_id' => $stage->id,
                        'services_goal' => (int) $stageData['amount'],
                        'needed_services' => $stageData['needed_services'],
                        'address' => $stageData['address'],

                    ]);
                    break;

            }
        }

        // Crear Imagenes
        foreach ($validated['images'] as $image) {
            $url = $this->firebase->uploadImage($image);
            $campaign->images()->create([
                'url' => $url,
            ]);
        }
        // Crear video
        if (isset($validated['video'])) {
            $videoUrl = $this->firebase->uploadVideo($validated['video']);
            $campaign->videos()->create([
                'url' => $videoUrl,
            ]);
        }
        // Crear documentos
        foreach ($validated['documents'] as $document) {
            $documentUrl = $this->firebase->uploadDocument($document);
            $campaign->documents()->create([
                'file_path' => $documentUrl,
            ]);
        }
        DB::commit();
        return response()->json([
            'message' => 'Campaña creada exitosamente.',
        ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error al crear la campaña: ' . $e->getMessage(),
            ], 500);
        }
    


    

return response()->json([
    'message' => 'Campaña creada exitosamente.',
    'campaign' => $campaign
    

], 201);
    }



public function desactivateCampaign(Campaign $campaign)
{
    $campaign->update(['status' => false]);

    return response()->json([
        'message' => 'Campaña desactivada exitosamente.',
    ], 201);
}


public function activateCampaign(Campaign $campaign)
{
    $campaign->update(['status' => true]);

    return response()->json([
        'message' => 'Campaña activada exitosamente.',
    ], 201);
}




public function updateStages(Request $request, Stage $stage)
    {
         $rules = [
        'title' => 'required|string',
        'description' => 'nullable|string',
        'deadline' => 'required|date',
    ];

    // Validación según el tipo de etapa
    switch ($stage->type) {
        case 1: // Monetaria
            $rules['amount'] = 'required|numeric';
            break;
        case 2: // Bienes
            $rules['amount'] = 'required|numeric';
            $rules['needed_goods'] = 'required|string';
            $rules['address'] = 'required|string';
            break;
        case 3: // Servicios
            $rules['needy_person'] = 'required|integer';
            $rules['type_of_service'] = 'required|string';
            $rules['address'] = 'required|string';
            break;
    }

    $request->validate($rules);

    // Actualizar etapa base
    $stage->update([
        'title' => $request->title,
        'description' => $request->description,
        'deadline' => $request->deadline,
    ]);

    // Actualizar la sub-etapa correspondiente
    switch ($stage->type) {
        case 1:
            $stage->monetary_stage()->update([
                'amount' => $request->amount,
            ]);
            break;
        case 2:
            $stage->goods_stage()->update([
                'amount' => $request->amount,
                'needed_goods' => $request->needed_goods,
                'address' => $request->address,
            ]);
            break;
        case 3:
            $stage->services_stage()->update([
                'needy_person' => $request->needy_person,
                'type_of_service' => $request->type_of_service,
                'address' => $request->address,
            ]);
            break;
    }

    return response()->json([
        'message' => 'Etapa actualizada exitosamente.',
        'stages' => $stage
    ],201);
}
    


public function destroyStage(Stage $stage)
    {
   
    $stage->monetary_stage()?->delete();
    $stage->goods_stage()?->delete();
    $stage->services_stage()?->delete();

   
    $stage->delete();

    return response()->json([
        'message' => 'Etapa eliminada exitosamente.',
        'stage' => $stage
    ],201);
    }
}



//formato del JSON: 

/*{
  "title": "Campaña para ayudar perritos",
  "description": "Recolectar cobijas y casitas, necesitamos de tu ayuda y colaboracion. Esperamos que pronto estos perritos puedan ser felices",
  "category": 3,
  "repeat": false,
  "stages": [
    {
      "title": "Etapa de bienes",
      "description": "Recolectar bienes",
      "deadline": "2025-06-10",
      "type": "goods",
      "amount": 50,
      "needed_goods": "Cobijas, casitas para perros",
      "address": "Refugio Canino, Calle 123"
    }
  ],
  "media_files": [
    {
      "url": "https://example.com/imagen.jpg",
      "type": "image"
    }
  ],
  "documents": [
    {
      "file_path": "/docs/tarea.pdf"
    }
  ]
}

*/ 
