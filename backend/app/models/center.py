from sqlalchemy import Integer, String, Float, Text
from sqlalchemy.orm import Mapped, mapped_column, relationship
from app.core.config import Base

class Centro(Base):
    __tablename__ = "centros"

    id: Mapped[int] = mapped_column(Integer, primary_key=True, index=True)
    nombre: Mapped[str] = mapped_column(String(150), nullable=False)
    direccion: Mapped[str] = mapped_column(String(255), nullable=False)
    ciudad: Mapped[str] = mapped_column(String(100), nullable=False)
    latitud: Mapped[float | None] = mapped_column(Float, nullable=True)
    # "float | None" significa que el campo puede ser un número o puede estar vacío, no todos los centros tienen coordenadas GPS
    longitud: Mapped[float | None] = mapped_column(Float, nullable=True)
    # Latitud y longitud las guardo para poder mostrar los centros en un mapa en el frontend más adelante
    materiales: Mapped[str | None] = mapped_column(Text, nullable=True)
    telefono: Mapped[str | None] = mapped_column(String(20), nullable=True)
    horario: Mapped[str | None] = mapped_column(String(200), nullable=True)

    reciclajes = relationship("Reciclaje", back_populates="centro")