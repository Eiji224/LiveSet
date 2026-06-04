from pydantic_settings import BaseSettings, SettingsConfigDict


class Settings(BaseSettings):
    PROJECT_NAME: str = "LiveSet API"
    DEBUG: bool = False

    DB_HOST: str
    DB_PORT: str
    DB_DATABASE: str
    DB_USERNAME: str
    DB_PASSWORD: str

    model_config = SettingsConfigDict(env_file=".env", env_file_encoding="utf-8", extra="ignore")


settings = Settings()