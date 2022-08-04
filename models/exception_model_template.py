class ExceptionModelTemplate:
    """
    Exception 情報モデル
    """
    id: str
    result: str
    code: str
    message: str
    response_code: int
    description: str

    def __init__(self):
        self.id = ''
        self.result = ''
        self.code = ''
        self.message = ''
        self.response_code = 0
        self.description = ''


