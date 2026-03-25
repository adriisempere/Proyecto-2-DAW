# Importaciones son similares a un require de PHP
from datetime import datetime, timedelta
from typing import Optional

from jose import JWTError, jwt
from passlib.context import CryptContext
from fastapi import Depends, HTTPException, status
from fastapi.security import OAuth2PasswordBearer
from sqlalchemy.orm import Session

from app.core.config import configuraciones, get_db

pwd_context = CryptContext(schemes=["bcrypt"], deprecated="auto") # Ésto es el motor de encriptación de contraseñas
oauth2_scheme = OAuth2PasswordBearer(tokenUrl="/api/auth/login") # Le dice a FastAPI donde está el endpoint de login

def hashear_password(password: str) -> str: # Funcion para hashear la contraseña
    return pwd_context.hash(password)

def verificar_password(plain: str, hashed: str) -> bool:
    # Cuando el usuario hace login escribe "1234" por ejemplo peor nosotros lo hasheamos antes
    return pwd_context.verify(plain, hashed) # Ésta función lo hashea, lo compara y devuelve bool

def crear_token(data: dict, expires_delta: Optional[timedelta] = None) -> str:
    to_encode = data.copy()
    expire = datetime.utcnow() + (
        expires_delta or timedelta(minutes=configuraciones.ACCESS_TOKEN_EXPIRE_MINUTES)
    )
    to_encode.update({"exp": expire})
    return jwt.encode(to_encode, configuraciones.SECRET_KEY, algorithm = configuraciones.ALGORITHM)

""" Cuando la sesión es correcta creo un token JWT - una cadena de texto firmada que Angular guarda y envía en cada petición
Coge por ejemplo el ID, le añade una fecha de expiración (60 min), lo firma con tu SECRET_KEY y devuelve el token como string
"""

from app.models.user import Usuario


def get_current_user(
    token: str = Depends(oauth2_scheme),
    db: Session = Depends(get_db),
) -> Usuario:
    credentials_exception = HTTPException(
        status_code=status.HTTP_401_UNAUTHORIZED,
        detail="Token inválido o expirado",
        headers={"WWW-Authenticate": "Bearer"},
    )
    try:
        payload = jwt.decode(token, configuraciones.SECRET_KEY, algorithms=[configuraciones.ALGORITHM])
        usuario_id: int = payload.get("sub")
        if usuario_id is None:
            raise credentials_exception
    except JWTError:
        raise credentials_exception

    usuario = db.query(Usuario).filter(Usuario.id == int(usuario_id)).first()
    if usuario is None:
        raise credentials_exception
    return usuario