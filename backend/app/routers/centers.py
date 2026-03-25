from fastapi import APIRouter, Depends, HTTPException, Query
from sqlalchemy.orm import Session
from typing import List
from app.core.config import get_db
from app.core.security import get_current_user
from app.models.center import Centro
from app.models.user import Usuario
from app.schemas.recycle import CentroCreate, CentroResponse

router = APIRouter(prefix="/api/centers", tags=["Centros"])


@router.get("/", response_model=List[CentroResponse])
def listar_centros(
    ciudad: str | None = Query(None),
    db: Session = Depends(get_db),
):
    query = db.query(Centro)
    if ciudad:
        query = query.filter(Centro.ciudad.ilike(f"%{ciudad}%"))
        # Es un LIKE case-sensitive, busca la ciudad sin importar mayúsculas o minúsculas
    return query.order_by(Centro.ciudad).all()


@router.get("/{centro_id}", response_model=CentroResponse)
# El valor entre llaves es un parámetro de ruta, si llamas a /api/centers/3 FastAPI extrae el 3 automáticamente
def detalle_centro(centro_id: int, db: Session = Depends(get_db)):
    centro = db.query(Centro).filter(Centro.id == centro_id).first()
    if not centro:
        raise HTTPException(status_code=404, detail="Centro no encontrado")
    return centro


@router.post("/", response_model=CentroResponse, status_code=201)
def crear_centro(
    data: CentroCreate,
    db: Session = Depends(get_db),
    usuario_actual: Usuario = Depends(get_current_user),
):
    if usuario_actual.rol != "admin": # Solo los admins pueden crear centros
        raise HTTPException(status_code=403, detail="Se requiere rol de administrador")
    centro = Centro(**data.model_dump())
    # Convierte el schema Pydantic en un diccionario y lo desempaqueta como argumentos
    db.add(centro)
    db.commit()
    db.refresh(centro)
    return centro