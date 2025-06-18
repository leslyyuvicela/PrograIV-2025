//Models
export interface BaseStage {
    id?: string; // ID del stage, si es que ya existe
    title: string;
    description: string;
    isActive?: boolean;
    deadline: Date; // Fecha límite para el stage, si es que aplica
    moneyGoal?: string; // Objetivo de recaudación en dinero, si es que aplica
    goodsGoal?: number; // Objetivo de recaudación en bienes, si es que aplica
    servicesGoal?: number; // Objetivo de recaudación en servicios, si es que aplica
    type: "money" | "goods" | "services";
    address?: string; // Solo para recaudaciones de tipo "goods" o "services"
    neededGoods?: string; // Solo para recaudaciones de tipo "goods"
    neededServices?: string; // Solo para recaudaciones de tipo "services"
}

/*
export interface StageUploadDTO{
    title: string;
    description: string;
    deadline: Date; // Fecha límite para el stage, si es que aplica
    type_stage: 1 | 2 | 3; // 1: money, 2: goods, 3: services
    amount: number; // Objetivo de recaudación, dependiendo del tipo
    needed_goods?: string; 
    address?: string;
    needy_person: number; 
    type_
}
    */

export interface MediaFile {
    id?: string; // ID del archivo, si es que ya existe
    url?: string; // URL del archivo
    file: File; // Archivo en sí, para subirlo
    type: "image" | "video"; // Tipo de archivo
}

export interface Document {
    id?: string; // ID del documento, si es que ya existe
    url: string; // URL del documento
}

export interface BaseFundraising {
    title: string;
    description: string;
    stages: BaseStage[];
    category?: string; // Categoría de la recaudación (es un id)
    mediaFiles: File[]; // Archivos multimedia para la recaudación
    documents: File[]; // Documentos para la recaudación
}

export interface FundraisingLoadData{
    title: string;
    description: string;
    stages: BaseStage[];
    category: string; // Categoría de la recaudación (es un id)
    images:File[]; // Archivos multimedia para la recaudación
    video?: File; // Archivos multimedia para la recaudación
    documents: File[] // Documentos para la recaudación
}


export interface FundraisingPreview {
    id: string;
    title: string;
    imageUrl: string;
    progress: number;
    moneyRaised?: number; // Ya que las recaudaciones pueden tener entre uno y tres tipos de contribuciones, cuando solo se pida un tipo o dos, los otros serán nulos.
    goodsRaised?: number;
    goodsType?: string; // Solo si es de tipo "goods"
    servicesRaised?: number;
}

// Response Types
export interface SaveFundraisingResponse {
    success: boolean;
    errors?: {
        title?: string;
        description?: string;
        stages?: string[];
        category?: string;
        mediaFiles?: string[];
        documents?: string[];
    };
}

