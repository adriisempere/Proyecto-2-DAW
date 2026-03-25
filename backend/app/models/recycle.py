from datetime import datetime
from sqlalchemy import Integer, Float, String, DateTime, ForeignKey, Enum
from sqlalchemy.orm import Mapped, mapped_column, relationship
from app.core.config import Base

class Reciclaje(Base):
    __tablename__ = "reciclajes"

    id: Mapped[int] = mapped_column(Integer, primary_key=True, index=True)
    usuario_id: Mapped[int] = mapped_column(Integer, ForeignKey("usuarios.id"), nullable=False)
    # ForeignKey("usuarios.id") vincula esta columna con el id de la tabla usuarios, igual que en SQL
    material: Mapped[str] = mapped_column(
        Enum("plastico", "papel", "vidrio", "metal", "organico", name="material_enum"),
        nullable=False,
        # Enum(...) solo permite esos valores exactos, si intentas guardar "cartón" dará error
    )
    cantidad_kg: Mapped[float] = mapped_column(Float, nullable=False)
    puntos_ganados: Mapped[float] = mapped_column(Float, default=0.0)
    centro_id: Mapped[int | None] = mapped_column(Integer, ForeignKey("centros.id"), nullable=True)
    # "centro_id" es opcional (nullable=True) porque se puede reciclar sin especificar un centro
    fecha: Mapped[datetime] = mapped_column(DateTime, default=datetime.utcnow)
    notas: Mapped[str | None] = mapped_column(String(500), nullable=True)

    usuario = relationship("Usuario", back_populates="reciclajes")
    centro = relationship("Centro", back_populates="reciclajes")
    # Ésto son relaciones inversas, le dicen a SQLAlchemy que este reciclaje pertenece a un usuario y a un centro

