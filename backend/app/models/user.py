from datetime import datetime
from sqlalchemy import String, Integer, DateTime, Float, Enum
from sqlalchemy.orm import Mapped, mapped_column, relationship
from app.core.config import Base

class Usuario(Base):
    __tablename__ = "usuarios" # Nombre de la tabla en PostgreSQL

    id: Mapped[int] = mapped_column(Integer, primary_key=True, index=True)
    # "Mapped[int]" Le dice a Python qué tipo de dato es
    nombre: Mapped[str] = mapped_column(String(100), nullable=False)
    # "mapped_column(...)" define las propiedade de la columna, igual que cuando escribes INT NOT NULL en SQL
    email: Mapped[str] = mapped_column(String(150), unique=True, nullable=False, index=True)
    # "index = True" crea un índice en esa columna para búsquedas más rápidas
    # "unique = True" no permite emails repetidos
    password_hash: Mapped[str] = mapped_column(String(255), nullable=False)
    puntos: Mapped[float] = mapped_column(Float, default=0.0)
    # "default = 0.0" valor por defecto si no se especifica
    rol: Mapped[str] = mapped_column(Enum("usuario", "admin", name="rol_enum"), default="usuario")
    creado_en: Mapped[datetime] = mapped_column(DateTime, default=datetime.utcnow)

    reciclajes = relationship("Reciclaje", back_populates="usuario", cascade= "all, delete")
    # Ésto le dice a SQLAlchemy que un usuario tiene muchos reciclajes. Es como un JOIN pero automático.