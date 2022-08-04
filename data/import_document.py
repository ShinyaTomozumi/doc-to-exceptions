# -*- coding: utf-8 -*-
# ドキュメントの読み込み
import yaml
from typing import List
from models.exception_model_template import ExceptionModelTemplate
from models.parameter_config import ParameterConfig


class ImportDocument:
    """
    ドキュメントの読み込みクラス
    """
    version: str
    copyright: str
    description: str
    type: str
    exceptions: List[ExceptionModelTemplate]
    parameter_config: ParameterConfig

    def __init__(self, parameter_config: ParameterConfig):
        """
        初期化
        :param parameter_config:
        """
        # 設定の初期化
        self.parameter_config = parameter_config
        self.exceptions = []

        # 読み込むドキュメントの種類によって、読み込む処理を変更する。
        if self.parameter_config.document_type == 'yaml':
            self._import_yaml()

    def _import_yaml(self):
        """
        Yamlファイルから読み込み
        :return:
        """
        # yamlファイルの読み込み
        with open(self.parameter_config.input_files_path, 'r') as yml:
            yaml_info = yaml.safe_load(yml)

        # 基本情報を定義する
        self.version = yaml_info['version']
        self.description = yaml_info['description']
        if 'copyright' in yaml_info:
            self.copyright = yaml_info['copyright']
        if 'author' in yaml_info:
            self.author = yaml_info['author']
        if 'type' in yaml_info:
            self.type = yaml_info['type']
        else:
            self.type = 'api'

        # Exception情報を作成する
        yaml_exceptions = yaml_info['exceptions']
        for key, exception_info in yaml_exceptions.items():
            # キーが存在しない場合はエラーを表示してcontinue
            if not exception_info:
                print('Exceptions is none: ' + key)
                continue

            # 必須パラメータチェック
            require_parameters = ['code', 'message']
            require_flg = True
            for require in require_parameters:
                if require not in exception_info:
                    print('Not set exception parameters: {}: '.format(require) + key)
                    require_flg = False
            if not require_flg:
                continue

            # Exceptionsの設定
            exceptions_template = ExceptionModelTemplate()
            exceptions_template.id = key
            exceptions_template.code = exception_info['code']
            exceptions_template.message = exception_info['message']

            # resultの設定。"result"が設定されていない場合はkeyをもとに設定する
            if 'result' in exception_info:
                exceptions_template.result = exception_info['result']

            # response codeの設定
            if 'response_code' in exception_info:
                exceptions_template.response_code = exception_info['response_code']

            # descriptionの設定
            if 'description' in exception_info:
                exceptions_template.description = exception_info['description']

            # Add exceptions
            self.exceptions.append(exceptions_template)
