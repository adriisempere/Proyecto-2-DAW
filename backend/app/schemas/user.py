"""
Un schema en FastAPI es una clase que define qué datos se esperan recibir o enviar en cada petición
Si el usuario manda un email inválido o le falta un campo, FastApi lo rechaza automáticamente.

Es diferente al modelo, el modelo es la tabla en la BDD, el schema es la validación de datos que entran y salen por la API.
"""
from datetime import datetime
from pydantic import BaseModel, EmailStr, Field

class LoginRequest(BaseModel):
    # Los datos que manda Angular cuando el usuario hace login
    email: EmailStr
    password: str


class Token(BaseModel):
    # Lo que devuelve la API tras un login correcto, el JWT
    access_token: str
    token_type: str = "bearer"


class UsuarioCreate(BaseModel):
    # Los datos para registrar un usuario nuevo, con validaciones de longitud
    nombre: str = Field(..., min_length=2, max_length=100)
    email: EmailStr
    password: str = Field(..., min_length=6)


class UsuarioResponse(BaseModel):
    # Lo que devuelve la API cuando consultas un usuario, nunca incluye el password_hash
    id: int
    nombre: str
    email: str
    puntos: float
    rol: str
    creado_en: datetime

    model_config = {"from_attributes": True}
    # Permite convertir un objeto de SQLAlchemy directamente a este schema
