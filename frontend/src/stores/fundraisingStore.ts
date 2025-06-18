import { FundraisingPreview, BaseFundraising, BaseStage } from "@/types/fundraisingTypes";
import { defineStore } from "pinia";
import { GetRecommendedFundraisings } from "@/services/fundraisingService";


// Datos de prueba
const sampleRecommendedFundraisings: FundraisingPreview[] = [
    {
        id: "1",
        title: "Recaudación para el Hospital Local",
        imageUrl: "https://firebasestorage.googleapis.com/v0/b/donamiga-91b4a.firebasestorage.app/o/Campaign_Images%2FRecaudacion1.png?alt=media&token=bfd66b4c-4c7d-4c0f-91d9-c83f82c20ab5",
        progress: 75,
        moneyRaised: 1500,
    },
    {
        id: "2",
        title: "Ayuda a los Animales Abandonados",
        imageUrl: "https://firebasestorage.googleapis.com/v0/b/donamiga-91b4a.firebasestorage.app/o/Campaign_Images%2FBulldog.jpeg?alt=media&token=21464fe3-a691-429d-b91d-3738304f1738",
        progress: 50,
        moneyRaised: 800,
        servicesRaised: 10,
        goodsRaised: 20,
        goodsType: "Alimentos para mascotas"
    },
    {
        id: "3",
        title: "Recaudación para la Educación Infantil",
        imageUrl: "https://firebasestorage.googleapis.com/v0/b/donamiga-91b4a.firebasestorage.app/o/Campaign_Images%2FRecaudacion3.png?alt=media&token=aa903371-cb91-484f-a7b0-f51f3ece8ed1",
        progress: 90,
        moneyRaised: 2000,
        servicesRaised: 5
    },
    {
        id: "4",
        title: "Apoyo a Víctimas de Desastres Naturales",
        imageUrl: "https://firebasestorage.googleapis.com/v0/b/donamiga-91b4a.firebasestorage.app/o/Campaign_Images%2FRecaudacion2.png?alt=media&token=93b43ad8-82f7-42ff-91f9-2ebe87a80571",
        progress: 30,
        goodsRaised: 100,
        goodsType: "Alimentos",
        moneyRaised: 500
    },
]

const sampleCategories = [
    {"id": "1", "name": "Educación"},
    {"id": "2", "name": "Salud"},
    {"id": "3", "name": "Animales"},
    {"id": "4", "name": "Desastres Naturales"},
]

export const useFundraisingStore = defineStore("fundraising", {
    state: () => ({
        recommendedFundraisings: sampleRecommendedFundraisings as FundraisingPreview[],
        myFundraisings: [] as FundraisingPreview[],
        editedFundraising: {
            title: "",
            description: "",
            stages: [],
            category: "",
            mediaFiles: [],
            documents: []
        } as BaseFundraising,
        editedStage:{
            title: "",
            description: "",
            isActive: false,
            type: "money" as "money" | "goods" | "services",
            address: "",
            neededGoods: "",
            neededServices: ""
        } as BaseStage,
        stageEditingMode: false,
        fundraisingCategories: sampleCategories as { id: string, name: string }[],
    }),
    actions:{
        startEditingFundraising(fundraising: BaseFundraising) {
            this.editedFundraising = { ...fundraising };
        },
        stopEditingFundraising() {
            this.editedFundraising = 
            {
                title: "",
                description: "",
                stages: [],
                mediaFiles: [],
                documents: []
            };
        },
        createFundraising(){
            this.editedFundraising = {
                title: "",
                description: "",
                stages: [],
                mediaFiles: [],
                documents: []
            };
        },
        clearEditedStage() {
            this.stageEditingMode = false;
            this.editedStage = {
                id: crypto.randomUUID(), // Genera un nuevo ID único para el stage
                title: "",
                description: "",
                deadline: new Date(), // Fecha actual como predeterminada
                isActive: false,
                type: "money",
                address: "",
                neededGoods: "",
                neededServices: ""
            };
        },
        startEditingStage(stageId: string) {
            const stage = this.editedFundraising.stages.find(stage => stage.id === stageId);
            if (stage) {
                this.editedStage = { ...stage };
                this.stageEditingMode = true;
            } else {
                console.error("Stage not found for editing");
            }
        },
        saveEditedStage() {
            if(!this.stageEditingMode){
                if (this.editedFundraising.stages) {
                    this.editedFundraising.stages.push({ ...this.editedStage });
                } else {
                    this.editedFundraising.stages = [{ ...this.editedStage }];
                }
            }
            else {
                const index = this.editedFundraising.stages.findIndex(stage => stage.id === this.editedStage.id);
                if (index !== -1) {
                    this.editedFundraising.stages[index] = { ...this.editedStage };
                }
            }
            this.clearEditedStage();
            //Ordenar las etapas según fecha límite
            this.editedFundraising.stages.sort((a, b) => new Date(a.deadline).getTime() - new Date(b.deadline).getTime());
        },
        deleteStage(id: string) {
            this.editedFundraising.stages = this.editedFundraising.stages.filter(stage => stage.id !== id);
        },
        async loadRecommendedFundraisings(){
            try{
                this.recommendedFundraisings = await GetRecommendedFundraisings();
            }
            catch (error) {
                console.error("Error loading recommended fundraisings:", error);
            }
        }
    },
    persist: true
}) 