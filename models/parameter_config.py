class ParameterConfig:
    """
    コマンドで受け取ったパラメータの設定
    """
    input_files_path: str  # 取り込むファイルのパス
    output_dir_path: str  # 書き出すフォルダの名称
    project: str  # 書き出すファイルのプロジェクトの種類
    document_type: str  # 取り込むドキュメントの種類

    def __init__(self):
        self.input_files_path = ''
        self.output_dir_path = ''
        self.project = ''
        self.document_type = 'yaml'  # デフォルトは「yaml」とする
