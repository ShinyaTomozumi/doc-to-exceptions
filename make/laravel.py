import os
import shutil

from data.import_document import ImportDocument
from models.parameter_config import ParameterConfig
from manager import text_manager


class Laravel:
    """
    Laravelの作成
    """
    _parameter_config: ParameterConfig
    _import_document: ImportDocument
    _template_dir: str

    def __init__(self, parameter_config: ParameterConfig, import_document: ImportDocument):
        """
        初期化
        :param parameter_config:
        :param import_document:
        """
        self._import_document = import_document
        self._parameter_config = parameter_config
        if self._parameter_config.output_dir_path == '':
            if self._parameter_config.project == 'lumen':
                self._parameter_config.output_dir_path = 'output_exception_lumen/app/Exceptions'
            else:
                self._parameter_config.output_dir_path = 'output_exception_laravel/app/Exceptions'
        # テンプレートソースのフォルダを指定する
        self._template_dir = os.path.dirname(__file__) + '/../template/laravel'

    def make(self):
        """
        ソースコードを作成する
        :return:
        """
        # ShowMessage
        print('Target Project: laravel')
        print('version: ' + self._import_document.version)
        if self._import_document.copyright != '':
            print('copyright: ' + self._import_document.copyright)
        if self._import_document.author != '':
            print('author: ' + self._import_document.author)
        print(self._import_document.description)

        # 以前に作成したファイル・フォルダを削除する
        if os.path.isdir(self._parameter_config.output_dir_path):
            shutil.rmtree(self._parameter_config.output_dir_path)

        # ソースコードの出力先のフォルダを作成する
        os.makedirs(self._parameter_config.output_dir_path, exist_ok=True)

        # 共通で使用するExceptionの基底ソースコードを作成する。
        # Handlerファイルの作成
        if self._parameter_config.project == 'lumen':
            template_dir_handler = os.path.dirname(__file__) + '/../template/lumen'
        else:
            template_dir_handler = self._template_dir

        template_file = open(template_dir_handler + '/Handler.php', 'r')
        template_source = template_file.read()
        source_file = open(self._parameter_config.output_dir_path + '/Handler.php', 'w')
        source_file.write(template_source)
        source_file.close()

        # ApiBaseExceptionファイルの作成
        template_file = open(self._template_dir + '/BaseApiException.php', 'r')
        template_source = template_file.read()
        source_file = open(self._parameter_config.output_dir_path + '/BaseApiException.php', 'w')
        source_file.write(template_source)
        source_file.close()

        # ApiExceptionFactoryファイルの作成
        template_file = open(self._template_dir + '/ApiExceptionFactory.php', 'r')
        template_source = template_file.read()
        source_file = open(self._parameter_config.output_dir_path + '/ApiExceptionFactory.php', 'w')
        source_file.write(template_source)
        source_file.close()

        # ErrorParametersファイルの作成
        template_file = open(self._template_dir + '/ErrorParameters.php', 'r')
        template_source = template_file.read()
        source_file = open(self._parameter_config.output_dir_path + '/ErrorParameters.php', 'w')
        source_file.write(template_source)
        source_file.close()

        # ソースコードの作成
        types = text_manager.get_pascal_name(self._import_document.type)
        for exception in self._import_document.exceptions:
            # ファイル名の作成
            class_name = types + text_manager.get_pascal_name(exception.id) + \
                        'Exception'

            # Exceptionファイルの書き出し先を作成する
            os.makedirs(self._parameter_config.output_dir_path + '/Api', exist_ok=True)

            # ソースコードの作成
            source_file = open(self._parameter_config.output_dir_path + '/Api/' + class_name + '.php', 'w')

            # Exceptionファイルのテンプレートソースコードを読み込む
            template_file = open(self._template_dir + '/Exceptions.php', 'r')
            template_source = template_file.read()

            # 各ソースコードの設定を行う
            template_source = template_source.replace('__class_name__', class_name)
            template_source = template_source.replace('__comment__', exception.description.replace('\n', '\n * '))
            template_source = template_source.replace('__code__', str(exception.code))
            template_source = template_source.replace('__message__', exception.message.rstrip('\n').replace('\n', '\\n'))

            # result が設定されていない場合はidを大文字にして設定する
            if exception.result == '':
                template_source = template_source.replace('__result__', 'ERR_' + exception.id.upper())
            else:
                template_source = template_source.replace('__result__', exception.result)

            # response_codeの設定
            if exception.response_code == 0:
                template_source = template_source.replace('__response_code__', '')
            else:
                template_source = template_source.replace('__response_code__', '$errorParameters->setHttpResponseCode('
                                                          + str(exception.response_code) + ');')

            # Exceptionファイルを作成する
            source_file.write(template_source)
            source_file.close()
