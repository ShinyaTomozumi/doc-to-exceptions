import re


def get_pascal_name(name: str) -> str:
    """
    指定した文字列をパスカル形式にする。
    :param name:
    :return:
    """
    return re.sub("_(.)", lambda x: x.group(1).upper(), name.capitalize())
