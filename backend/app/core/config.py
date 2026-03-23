from pydantic_settings import BaseSettings
from sqlalchemy import create_engine
from sqlalchemy.orm import sessionmaker, DeclarativeBase
from typing import Generator

class Configuraciones(BaseSettings):
    DATABASE_URL: str
    SECRET_KEY: str
    ALGORITHM: str = "HS256"
    ACCESS_TOKEN_EXPIRE_MINUTES: int = 60

    class Config:
        env_file = ".env"

configuraciones = Configuraciones()

motor = create_engine(configuraciones.DATABASE_URL)
SesionLocal = sessionmaker(autocommit=False, autoflush=False, bind=motor)

class Base(DeclarativeBase):
    pass

def get_db() -> Generator:
    db = SesionLocal()
    try:
        yield db
    finally:
        db.close()