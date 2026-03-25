from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
from app.core.config import Base, motor
from app.routers import auth, recycle, ranking, centers

Base.metadata.create_all(bind=motor)
# Cuanto arranca la app crea todas las tablas en PostgreSQL automáticamente si no existen

app = FastAPI(
    title="GreenPoints API",
    description="API para la plataforma de gamificación del reciclaje",
    version="2.0.0",
)

app.add_middleware(
    CORSMiddleware,
    # Permite que Angular en localhost:4200 pueda hacer peticiones a la API
    allow_origins=["http://localhost:4200"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

app.include_router(auth.router)
app.include_router(recycle.router)
app.include_router(ranking.router)
app.include_router(centers.router)
# Registro de cada router con us endpoints

@app.get("/", tags=["Health"])
def root():
    return {"status": "ok", "mensaje": "GreenPoints API v2 funcionando"}