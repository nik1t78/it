"""Модели БД — перенос схемы Yii-модулей оригинального сайта (БД `szn`)."""
from datetime import datetime

from flask_login import UserMixin
from flask_sqlalchemy import SQLAlchemy

db = SQLAlchemy()


class Page(db.Model):
    """Модуль pages: дерево страниц (nested set lft/rgt, как в Yii Pages)."""
    __tablename__ = 'pages'
    id = db.Column(db.Integer, primary_key=True)
    title = db.Column(db.String(255), nullable=False)
    name = db.Column(db.String(255))            # alias / slug
    anons = db.Column(db.Text)
    body = db.Column(db.Text)
    parent_id = db.Column(db.Integer, db.ForeignKey('pages.id'))
    level = db.Column(db.Integer, default=0)
    lft = db.Column(db.Integer, default=0)
    rgt = db.Column(db.Integer, default=0)
    menu_enable = db.Column(db.Boolean, default=True)
    map_enable = db.Column(db.Boolean, default=True)
    redirect = db.Column(db.String(255))
    status = db.Column(db.SmallInteger, default=1)  # 1 — опубликовано
    author_id = db.Column(db.Integer)
    create_time = db.Column(db.DateTime, default=datetime.utcnow)
    update_time = db.Column(db.DateTime, default=datetime.utcnow, onupdate=datetime.utcnow)

    children = db.relationship('Page', backref=db.backref('parent', remote_side=[id]))

    @property
    def url(self):
        return f'/page/{self.id}'


class MenuItem(db.Model):
    """Модуль menu: пункты меню."""
    __tablename__ = 'menu_items'
    id = db.Column(db.Integer, primary_key=True)
    title = db.Column(db.String(255), nullable=False)
    link = db.Column(db.String(255))
    parent_id = db.Column(db.Integer, db.ForeignKey('menu_items.id'))
    sort = db.Column(db.Integer, default=100)
    active = db.Column(db.Boolean, default=True)
    css = db.Column(db.String(64))
    children = db.relationship('MenuItem', backref=db.backref('parent', remote_side=[id]),
                               order_by='MenuItem.sort')


class IBlock(db.Model):
    """Модуль iblock: информационные блоки (новости и т.п.)."""
    __tablename__ = 'iblock'
    id = db.Column(db.Integer, primary_key=True)
    code = db.Column(db.String(64), unique=True)
    name = db.Column(db.String(255))
    elements = db.relationship('IBlockElement', backref='iblock')


class IBlockSection(db.Model):
    __tablename__ = 'iblock_section'
    id = db.Column(db.Integer, primary_key=True)
    iblock_id = db.Column(db.Integer, db.ForeignKey('iblock.id'))
    name = db.Column(db.String(255))
    code = db.Column(db.String(64))


class IBlockElement(db.Model):
    __tablename__ = 'iblock_element'
    id = db.Column(db.Integer, primary_key=True)
    iblock_id = db.Column(db.Integer, db.ForeignKey('iblock.id'))
    section_id = db.Column(db.Integer, db.ForeignKey('iblock_section.id'))
    name = db.Column(db.String(255), nullable=False)
    preview_text = db.Column(db.Text)
    detail_text = db.Column(db.Text)
    preview_picture = db.Column(db.String(255))
    active = db.Column(db.Boolean, default=True)
    published = db.Column(db.Boolean, default=True)
    sort = db.Column(db.Integer, default=100)
    created_at = db.Column(db.DateTime, default=datetime.utcnow)


class User(UserMixin, db.Model):
    """Модуль user."""
    __tablename__ = 'user'
    id = db.Column(db.Integer, primary_key=True)
    login = db.Column(db.String(100), unique=True, nullable=False)
    full_name = db.Column(db.String(100))
    email = db.Column(db.String(100))
    password_hash = db.Column(db.String(255), nullable=False)
    role = db.Column(db.String(32), default='user')  # admin | editor | user
    region = db.Column(db.String(100))
    address = db.Column(db.String(255))
    phone_num = db.Column(db.String(64))

    def set_password(self, raw):
        from werkzeug.security import generate_password_hash
        self.password_hash = generate_password_hash(raw)

    def check_password(self, raw):
        from werkzeug.security import check_password_hash
        return check_password_hash(self.password_hash, raw)


class Faq(db.Model):
    """Модуль faq: вопросы-ответы."""
    __tablename__ = 'faq'
    id = db.Column(db.Integer, primary_key=True)
    q_author = db.Column(db.String(100))
    q_email = db.Column(db.String(100))
    q_date = db.Column(db.DateTime, default=datetime.utcnow)
    question = db.Column(db.Text, nullable=False)
    a_author = db.Column(db.String(100))
    a_date = db.Column(db.DateTime)
    answer = db.Column(db.Text)
    approved = db.Column(db.Boolean, default=False)


class Poll(db.Model):
    """Модуль polls: голосования."""
    __tablename__ = 'polls'
    id = db.Column(db.Integer, primary_key=True)
    title = db.Column(db.String(255))
    question = db.Column(db.Text)
    active_from = db.Column(db.DateTime, default=datetime.utcnow)
    active_to = db.Column(db.DateTime)
    active = db.Column(db.Boolean, default=True)
    answers = db.relationship('PollAnswer', backref='poll')


class PollAnswer(db.Model):
    __tablename__ = 'polls_answers'
    id = db.Column(db.Integer, primary_key=True)
    poll_id = db.Column(db.Integer, db.ForeignKey('polls.id'))
    title = db.Column(db.String(255))
    votes = db.relationship('PollVote', backref='answer')


class PollVote(db.Model):
    __tablename__ = 'polls_count'
    id = db.Column(db.Integer, primary_key=True)
    answer_id = db.Column(db.Integer, db.ForeignKey('polls_answers.id'))
    ip = db.Column(db.String(45))
    timestamp = db.Column(db.DateTime, default=datetime.utcnow)


class Banner(db.Model):
    """Модуль banner."""
    __tablename__ = 'banner'
    id = db.Column(db.Integer, primary_key=True)
    title = db.Column(db.String(255))
    url = db.Column(db.String(255))
    image = db.Column(db.String(255))
    description = db.Column(db.Text)
    position = db.Column(db.String(64), default='main')
    sort = db.Column(db.Integer, default=100)


class Reception(db.Model):
    """Модуль reception: обращения граждан."""
    __tablename__ = 'reception'
    id = db.Column(db.Integer, primary_key=True)
    subject = db.Column(db.String(255))
    fio = db.Column(db.String(255))
    address = db.Column(db.String(255))
    phone = db.Column(db.String(64))
    email = db.Column(db.String(100))
    body_text = db.Column(db.Text)
    date = db.Column(db.DateTime, default=datetime.utcnow)


class Textblock(db.Model):
    """Модуль textblock."""
    __tablename__ = 'textblock'
    id = db.Column(db.Integer, primary_key=True)
    code = db.Column(db.String(64), unique=True)
    name = db.Column(db.String(255))
    body = db.Column(db.Text)
    url = db.Column(db.String(255))


class Setting(db.Model):
    """Модуль settings: ключ-значение."""
    __tablename__ = 'settings_params'
    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(100), unique=True)
    title = db.Column(db.String(255))
    value = db.Column(db.Text)
    sort = db.Column(db.Integer, default=100)
