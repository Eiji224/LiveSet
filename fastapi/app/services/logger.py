import logging


logger = logging.getLogger("uvicorn.error")

def write_log(body):
    logger.info(body)