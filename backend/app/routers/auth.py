from fastapi import APIRouter, Depends, HTTPException, status
from sqlalchemy.orm import Session
from app.core.config import get_db
from app.core.security import get_current_user, verificar_password, crear_token, hashear_password
from app.models.user import Usuario
from app.schemas.user import LoginRequest, Token, UsuarioCreate, UsuarioResponse

router = APIRouter(prefix="/api/auth", tags=["Autenticación"])
# prefix="/api/auth" todos los endpoints de este router empezarán así

# ENDPOINT DE REGISTRO
@router.post("/register", response_model=UsuarioResponse, status_code=201)
def registrar(data: UsuarioCreate, db: Session = Depends(get_db)):
    # FastAPI abre una sesión de BD automáticamente y la cierra al terminar
    if db.query(Usuario).filter(Usuario.email == data.email).first():
        # Es el equivalente a SELECT * FROM usuarios WHERE email = ?
        raise HTTPException(status_code=400, detail="El email ya está registrado")
        # Si el email existe lanza un error 400 y si no, crea el usuario lo guarda y lo devuelve

    usuario = Usuario(
        nombre=data.nombre,
        email=data.email,
        password_hash=hashear_password(data.password),
    )
    db.add(usuario)
    db.commit()
    db.refresh(usuario)
    return usuario

# ENDPOINT DE LOGIN
# Busca el usuario por email en la BD
@router.post("/login", response_model=Token)
def login(data: LoginRequest, db: Session = Depends(get_db)):
    usuario = db.query(Usuario).filter(Usuario.email == data.email).first()
    if not usuario or not verificar_password(data.password, usuario.password_hash):
        raise HTTPException(
            status_code=status.HTTP_401_UNAUTHORIZED,
            detail="Email o contraseña incorrectos",
        )
    # Si no existe o la contraseña falla devuelve un 401
    token = crear_token({"sub": str(usuario.id)})
    # Si todo está correcto se genera un JWT con el ID
    return {"access_token": token, "token_type": "bearer"}
    # Devuelve el token para que Angular lo guarde


@router.get("/me", response_model=UsuarioResponse)
def me(usuario_actual: Usuario = Depends(get_current_user)):
    return usuario_actual

# MAS TARDE