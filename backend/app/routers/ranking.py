from fastapi import APIRouter, Depends, Query
from sqlalchemy.orm import Session
from app.core.config import get_db
from app.core.security import get_current_user
from app.models.user import Usuario

router = APIRouter(prefix="/api/ranking", tags=["Ranking"])


@router.get("/")
def ranking_global(
    limit: int = Query(default=10, ge=1, le=100),
    # Significa mínimo 1 y máximo 100
    db: Session = Depends(get_db),
):
    usuarios = (
        db.query(Usuario)
        .order_by(Usuario.puntos.desc())
        .limit(limit)
        .all()
    )
    return [
        {
            "posicion": idx + 1,
            "id": u.id,
            "nombre": u.nombre,
            "puntos": u.puntos,
        }
        for idx, u in enumerate(usuarios)
        # Recorre la lista añadiendo el número de posición automáticamente, idx empieza por 0 por eso sumamos 1
    ]


@router.get("/mi-posicion")
# Cuenta cuantos usuarios tienen más puntos que tú y le suma 1
def mi_posicion(
    db: Session = Depends(get_db),
    usuario_actual: Usuario = Depends(get_current_user),
):
    posicion = (
        db.query(Usuario).filter(Usuario.puntos > usuario_actual.puntos).count() + 1
    )
    total = db.query(Usuario).count()
    return {
        "posicion": posicion,
        "total_usuarios": total,
        "puntos": usuario_actual.puntos,
    }