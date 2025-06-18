import { SaveFundraisingResponse } from "@/types/fundraisingTypes"
import { BaseFundraising, FundraisingLoadData, FundraisingPreview } from "@/types/fundraisingTypes";
import api from '@/api/axios';
import axios from "axios";


export const SaveFundraising = async(fundraising: FundraisingLoadData): Promise<SaveFundraisingResponse> => {
    try{
    // Simulación de una llamada a la API para guardar una recaudación
    const formData = new FormData();
    formData.append("title", fundraising.title);
    formData.append("description", fundraising.description);
    formData.append("category", fundraising.category);
    fundraising.stages.forEach((stage, index) => {
        formData.append(`stages[${index}][title]`, stage.title);
        formData.append(`stages[${index}][description]`, stage.description);
        formData.append(`stages[${index}][type]`, stage.type);
        //Convertir la fecha a un formato ISO 8601
        formData.append(`stages[${index}][deadline]`, new Date(stage.deadline).toISOString());
        if (stage.type === "goods") {
            formData.append(`stages[${index}][goods_goal]`, stage.goodsGoal?.toString() || "0");
            formData.append(`stages[${index}][needed_goods]`, stage.neededGoods || "");
            formData.append(`stages[${index}][address]`, stage.address || "");
        } else if (stage.type === "services") {
            formData.append(`stages[${index}][needed_services]`, stage.neededServices || "");
            formData.append(`stages[${index}][address]`, stage.address || "");
            formData.append(`stages[${index}][services_goal]`, String(stage.servicesGoal || 0));
        } else if (stage.type === "money") {
            formData.append(`stages[${index}][money_goal]`, stage.moneyGoal || "");
        }
    });
    fundraising.images.forEach((file, index) => {
        formData.append(`images[${index}]`, file);
    });
    if (fundraising.video) {
        formData.append("video", fundraising.video);
    }
    fundraising.documents.forEach((file, index) => {
        formData.append(`documents[${index}]`, file);
    });
    //cambiar timeout a 1 minuto // 60 segundos
    await api.post("/create-fundraising", formData, {
        headers: {
            'Content-Type': 'multipart/form-data'
        },
        timeout: 60000, // 60 segundos
    });

    
    /*
    const axiosConfig = {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
    };
    const response = await axios.post("https://webhook.site/b0ccae5b-3c1c-4716-ac5d-669d84473141", formData, axiosConfig);
*/
    return {
        success: true,
    };
} catch (error: any) {
    console.error('Error saving fundraising:', error);
    
    if (!error.response) {
        // Network error or no response from server
        console.error('Network error or no response from server:', error);
        return {
            success: false,
        };
    }
    
    // Handle specific error responses
    if (error.response && error.response.status === 422) {
        return {
            success: false,
            errors: error.response.data.errors,
        };
    }
    
    return {
        success: false,
    };
}

}

export const GetRecommendedFundraisings = async (): Promise<FundraisingPreview[]> => {
    try {
        const response = await api.get<FundraisingPreview[]>('/recommended-fundraisings');
        return response.data;
    } catch (error) {
        console.error('Error fetching recommended fundraisings:', error);
        return [];
    }
}