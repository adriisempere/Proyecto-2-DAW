from fastapi import APIRouter, Depends
from sqlalchemy.orm import Session
from typing import List
from app.core.config import get_db
from app.core.security import get_current_user
from app.models.user import Usuario
from app.models.recycle import Reciclaje
from app.schemas.recycle import ReciclajeCreate, ReciclajeResponse, PUNTOS_POR_KG

router = APIRouter(prefix="/api/recycle", tags=["Reciclaje"])


@router.post("/", response_model=ReciclajeResponse, status_code=201)
def registrar_reciclaje(
    data: ReciclajeCreate,
    db: Session = Depends(get_db),
    usuario_actual: Usuario = Depends(get_current_user),
    # Protege el endpoint, solo usuarios con JWT válido pueden usarlo
):
    puntos = round(PUNTOS_POR_KG[data.material] * data.cantidad_kg, 2)
    # Calcula los puntos según material y kilos

    reciclaje = Reciclaje(
        usuario_id=usuario_actual.id,
        material=data.material,
        cantidad_kg=data.cantidad_kg,
        puntos_ganados=puntos,
        centro_id=data.centro_id,
        notas=data.notas,
    )
    db.add(reciclaje)
    # Despues de guardar el reciclaje
    usuario_actual.puntos = round(usuario_actual.puntos + puntos, 2)
    # Suma los puntos al usuario automáticamente
    db.commit()
    db.refresh(reciclaje)
    return reciclaje


@router.get("/mis-reciclajes", response_model=List[ReciclajeResponse])
def mis_reciclajes(
    db: Session = Depends(get_db),
    usuario_actual: Usuario = Depends(get_current_user),
):
    return (
        db.query(Reciclaje)
        .filter(Reciclaje.usuario_id == usuario_actual.id)
        .order_by(Reciclaje.fecha.desc())
        # Devuelve los reciclajes del más reciente al más antiguo
        .all()
    )