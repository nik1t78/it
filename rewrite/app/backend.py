"""Админка — аналог Yii-модуля backend (управление контентом)."""
from functools import wraps

from flask import (Blueprint, render_template, request, redirect, url_for,
                   abort, flash)
from flask_login import LoginManager, login_user, logout_user, login_required, current_user

from .db import db, Page, MenuItem, IBlockElement, Faq, Poll, PollAnswer, Banner, Textblock, Setting, User, Reception

bp = Blueprint('backend', __name__)

login_manager = LoginManager()
login_manager.login_view = 'backend.login'


@login_manager.user_loader
def load_user(user_id):
    return User.query.get(int(user_id))


def admin_required(f):
    @wraps(f)
    def decorated(*args, **kwargs):
        if not current_user.is_authenticated or current_user.role != 'admin':
            abort(403)
        return f(*args, **kwargs)
    return decorated


@bp.app_context_processor
def inject_backend_menu():
    return {}


# --- авторизация -----------------------------------------------------------
@bp.route('/login', methods=['GET', 'POST'])
def login():
    if request.method == 'POST':
        user = User.query.filter_by(login=request.form.get('login')).first()
        if user and user.check_password(request.form.get('password', '')):
            login_user(user)
            return redirect(url_for('backend.dashboard'))
        flash('Неверный логин или пароль')
    return render_template('backend/login.html')


@bp.route('/logout')
@login_required
def logout():
    logout_user()
    return redirect(url_for('frontend.index'))


@bp.route('/')
@login_required
def dashboard():
    stats = dict(
        pages=Page.query.count(),
        news=IBlockElement.query.count(),
        faq=Faq.query.count(),
        receptions=Reception.query.count(),
    )
    return render_template('backend/dashboard.html', stats=stats)


# --- CRUD ------------------------------------------------------------------
def _crud(name, model, fields, template_prefix, label):
    @bp.route(f'/{name}')
    @login_required
    def index():
        items = model.query.order_by(model.id.desc()).all()
        return render_template(f'{template_prefix}/index.html', items=items,
                               fields=fields, label=label, name=name)

    @bp.route(f'/{name}/create', methods=['GET', 'POST'])
    @login_required
    def create():
        if request.method == 'POST':
            obj = model(**{f: request.form.get(f) for f in fields})
            db.session.add(obj)
            db.session.commit()
            return redirect(url_for(f'backend.{name}_index'))
        return render_template(f'{template_prefix}/form.html', obj=None,
                               fields=fields, label=label, name=name)

    @bp.route(f'/{name}/<int:oid>/edit', methods=['GET', 'POST'])
    @login_required
    def edit(oid):
        obj = model.query.get_or_404(oid)
        if request.method == 'POST':
            for f in fields:
                setattr(obj, f, request.form.get(f))
            db.session.commit()
            return redirect(url_for(f'backend.{name}_index'))
        return render_template(f'{template_prefix}/form.html', obj=obj,
                               fields=fields, label=label, name=name)

    @bp.route(f'/{name}/<int:oid>/delete', methods=['POST'])
    @admin_required
    def delete(oid):
        obj = model.query.get_or_404(oid)
        db.session.delete(obj)
        db.session.commit()
        return redirect(url_for(f'backend.{name}_index'))

    # give stable endpoint names
    index.__name__ = f'{name}_index'
    create.__name__ = f'{name}_create'
    edit.__name__ = f'{name}_edit'
    delete.__name__ = f'{name}_delete'


_crud('pages', Page, ['title', 'name', 'anons', 'body'], 'backend/crud', 'Страницы')
_crud('menu', MenuItem, ['title', 'link', 'sort'], 'backend/crud', 'Меню')
_crud('news', IBlockElement, ['name', 'preview_text', 'detail_text'], 'backend/crud', 'Новости')
_crud('banners', Banner, ['title', 'url', 'image', 'position'], 'backend/crud', 'Баннеры')
_crud('textblocks', Textblock, ['code', 'name', 'body'], 'backend/crud', 'Текстовые блоки')
_crud('settings', Setting, ['name', 'title', 'value'], 'backend/crud', 'Настройки')


@bp.route('/faq/<int:oid>/approve', methods=['POST'])
@login_required
def faq_approve(oid):
    item = Faq.query.get_or_404(oid)
    item.approved = True
    item.a_author = current_user.full_name or current_user.login
    from datetime import datetime
    item.a_date = datetime.utcnow()
    item.answer = request.form.get('answer', '')
    db.session.commit()
    return redirect(url_for('backend.faq_index'))


@bp.route('/faq')
@login_required
def faq_index():
    items = Faq.query.order_by(Faq.q_date.desc()).all()
    return render_template('backend/faq.html', items=items)


@bp.route('/receptions')
@login_required
def reception_index():
    items = Reception.query.order_by(Reception.date.desc()).all()
    return render_template('backend/receptions.html', items=items)


def register(bp):
    pass
