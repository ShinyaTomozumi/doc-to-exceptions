#!/usr/bin/env python3
# -*- coding: utf-8 -*-
import sys
import os

from data.import_document import ImportDocument
from make.laravel import Laravel
from models.parameter_config import ParameterConfig

if __name__ == '__main__':
    """
    Main function
    """

    # Show console log
    print('Start doc_to_exceptions...')

    # パラメータを受け取る
    args = sys.argv

    # 引数の初期化
    parameter_config = ParameterConfig()

    # 受け取ったパラメータを引数に設定する。
    i = 0
    for arg in args:
        # 言語タイプを設定する
        if arg == '-project':
            if (i + 1) < len(args):
                parameter_config.project = args[i + 1]
        # ファイルのパスを取得する
        if arg == '-i':
            if (i + 1) < len(args):
                parameter_config.input_files_path = args[i + 1]
        # 出力先のパスを取得する
        if arg == '-o':
            if (i + 1) < len(args):
                parameter_config.output_dir_path = args[i + 1]
        # 取り込むドキュメントの種類を設定する
        if arg == '-doc':
            if (i + 1) < len(args):
                parameter_config.document_type = args[i + 1]
        i += 1

    # 取り込むファイルのパスが設定されていない場合はエラーを返却する
    if parameter_config.input_files_path == '':
        print('Set the file path (-i).')
        exit()

    # 取り込むファイルが存在しない場合はエラーを返却する
    if not os.path.isfile(parameter_config.input_files_path):
        print(f'The specified file does not exist. {parameter_config.input_files_path}')
        exit()

    # 取り込むファイルの拡張子をチェックして一致していない場合はエラーを返却する
    if parameter_config.document_type == 'yaml' \
            and not parameter_config.input_files_path.endswith('yaml') \
            and not parameter_config.input_files_path.endswith('yml'):
        # Yamlファイル
        print('The specified file is not a "yaml" or "yml" file.')
        exit()
    elif parameter_config.document_type == 'excel' \
            and not parameter_config.input_files_path.endswith('xlsx'):
        # エクセルファイル
        print('The specified file is not a "xlsx" file.')
        exit()

    # ドキュメントからExceptionsを読み込む
    import_document = ImportDocument(parameter_config)

    # Exceptionファイルの作成
    if parameter_config.project == 'laravel' \
            or parameter_config.project == 'lumen':
        # For laravel or lumen
        laravel = Laravel(parameter_config, import_document)
        laravel.make()
    else:
        print('The specified project is not supported.')
        exit()

    # Show finish message.
    print('Finish doc_to_exceptions...')
