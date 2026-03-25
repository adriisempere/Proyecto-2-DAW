# Importaciones
from pydantic_settings import BaseSettings
from sqlalchemy import create_engine
from sqlalchemy.orm import sessionmaker, DeclarativeBase
from typing import Generator

"""
Lee las variables del archivo .env automáticamente. 
Por eso DATABASE_URL y SECRET_KEY no tienen valor por defecto — vendrán del .env
"""

class Configuraciones(BaseSettings):
    DATABASE_URL: str
    SECRET_KEY: str
    ALGORITHM: str = "HS256"
    ACCESS_TOKEN_EXPIRE_MINUTES: int = 60

    class Config:
        env_file = ".env"

configuraciones = Configuraciones()

#Motor es la conexión a PostgreSQL.
motor = create_engine(configuraciones.DATABASE_URL)
#SesionLocal es la fábrica de sesiones — cada petición HTTP abrirá una sesión y la cerrará al terminar.
SesionLocal = sessionmaker(autocommit=False, autoflush=False, bind=motor)

#Clase de la que heredarán todos los modelos (tablas).
class Base(DeclarativeBase):
    pass

# get_db es lo que FastAPI usará para inyectar la sesión en cada endpoint.
def get_db() -> Generator:
    db = SesionLocal()
    try:
        yield db
    finally:
        db.close()