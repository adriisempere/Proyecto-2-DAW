from datetime import datetime
from pydantic import BaseModel, Field
from typing import Literal

# Literal permite definir que un campo solo puede tener ciertos valores exactos, igual que el Enum del modelo pero para la validación de entrada
MATERIALES = Literal["plastico", "papel", "vidrio", "metal", "organico"]

PUNTOS_POR_KG: dict[str, float] = {
    "plastico": 10.0,
    "papel": 5.0,
    "vidrio": 8.0,
    "metal": 15.0,
    "organico": 3.0,
}

# Aqui están los puntos que ganará el usuario según el material.

class ReciclajeCreate(BaseModel):
    material: MATERIALES
    cantidad_kg: float = Field(..., gt=0, le=1000)
    # "gt=0" significa greater than 0, no se puede registrar negativo
    # "le=1000" significa less or equal than 1000
    centro_id: int | None = None
    notas: str | None = Field(None, max_length=500)


class ReciclajeResponse(BaseModel):
    id: int
    material: str
    cantidad_kg: float
    puntos_ganados: float
    centro_id: int | None
    fecha: datetime
    notas: str | None

    model_config = {"from_attributes": True}


class CentroCreate(BaseModel):
    nombre: str = Field(..., min_length=2, max_length=150)
    direccion: str = Field(..., max_length=255)
    ciudad: str = Field(..., max_length=100)
    latitud: float | None = None
    longitud: float | None = None
    materiales: str | None = None
    telefono: str | None = Field(None, max_length=20)
    horario: str | None = Field(None, max_length=200)


class CentroResponse(BaseModel):
    id: int
    nombre: str
    direccion: str
    ciudad: str
    latitud: float | None
    longitud: float | None
    materiales: str | None
    telefono: str | None
    horario: str | None

    model_config = {"from_attributes": True}