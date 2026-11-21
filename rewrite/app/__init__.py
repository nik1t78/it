"""Приложение — переписанный сайт Управления социальной политики Липецкой области.

Оригинал: PHP 5 + Yii 1.1.16 (CMS на модулях: pages, menu, iblock, user, faq,
polls, banner, reception, settings, textblock, fotogallery, contest, filemanager).
Переписано на Python 3 + Flask + SQLAlchemy (SQLite по умолчанию, MySQL через
DATABASE_URL).
"""
import os

from flask import Flask


def create_app(test_config=None):
    app = Flask(__name__,
                template_folder=os.path.join(os.path.dirname(os.path.dirname(__file__)), 'templates'),
                static_folder=os.path.join(os.path.dirname(os.path.dirname(__file__)), 'static'))

    app.config.from_mapping(
        SECRET_KEY=os.environ.get('SECRET_KEY', 'dev-key-change-me'),
        SQLALCHEMY_DATABASE_URI=os.environ.get(
            'DATABASE_URL',
            'sqlite:///' + os.path.join(os.path.dirname(os.path.dirname(__file__)), 'usp.db')),
        SQLALCHEMY_TRACK_MODIFICATIONS=False,
    )

    if test_config:
        app.config.update(test_config)

    from . import db as db_module
    db_module.db.init_app(app)

    from .frontend import bp as frontend_bp
    from .backend import bp as backend_bp, login_manager
    app.register_blueprint(frontend_bp)
    app.register_blueprint(backend_bp, url_prefix='/backend')

    login_manager.init_app(app)
    login_manager.login_view = 'backend.login'

    with app.app_context():
        db_module.db.create_all()

    return app
