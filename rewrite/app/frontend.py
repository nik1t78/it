"""Публичная (фронтенд) часть сайта — аналог Yii-модулей pages/iblock/faq/polls/reception."""
from flask import Blueprint, render_template, request, redirect, url_for, abort, make_response

from .db import db, Page, MenuItem, IBlock, IBlockElement, Faq, Poll, Banner, Textblock, Setting, Reception

bp = Blueprint('frontend', __name__)


def site_settings():
    return {s.name: s.value for s in Setting.query.all()}


@bp.app_context_processor
def inject_globals():
    return dict(
        main_menu=MenuItem.query.filter_by(active=True).order_by(MenuItem.sort).all(),
        settings=site_settings(),
    )


@bp.route('/')
def index():
    news = (IBlockElement.query.join(IBlock)
            .filter(IBlock.code == 'news', IBlockElement.published.is_(True))
            .order_by(IBlockElement.created_at.desc()).limit(10).all())
    banners = Banner.query.filter_by(position='main').order_by(Banner.sort).all()
    poll = Poll.query.filter_by(active=True).first()
    return render_template('index.html', news=news, banners=banners, poll=poll)


@bp.route('/page/<int:page_id>')
def view_page(page_id):
    page = Page.query.get_or_404(page_id)
    if page.redirect:
        return redirect(page.redirect)
    return render_template('page.html', page=page)


@bp.route('/news/')
def news_list():
    ib = IBlock.query.filter_by(code='news').first()
    elements = []
    if ib:
        page = request.args.get('page', 1, type=int)
        query = (IBlockElement.query.filter_by(iblock_id=ib.id, published=True)
                 .order_by(IBlockElement.created_at.desc()))
        total = query.count()
        elements = query.offset((page - 1) * 20).limit(20).all()
        pages = (total + 19) // 20
        return render_template('news/list.html', ib=ib, elements=elements,
                               page=page, pages=pages)
    abort(404)


@bp.route('/news/<int:eid>')
def news_detail(eid):
    element = IBlockElement.query.get_or_404(eid)
    return render_template('news/detail.html', element=element)


@bp.route('/faq/')
def faq_list():
    items = Faq.query.filter_by(approved=True).order_by(Faq.q_date.desc()).all()
    return render_template('faq/list.html', items=items)


@bp.route('/faq/ask', methods=['GET', 'POST'])
def faq_ask():
    if request.method == 'POST':
        f = Faq(q_author=request.form.get('name'),
                q_email=request.form.get('email'),
                question=request.form.get('question'))
        db.session.add(f)
        db.session.commit()
        return redirect(url_for('frontend.faq_list'))
    return render_template('faq/ask.html')


@bp.route('/poll/vote', methods=['POST'])
def poll_vote():
    answer_id = request.form.get('answer_id', type=int)
    from .db import PollAnswer, PollVote
    answer = PollAnswer.query.get_or_404(answer_id)
    db.session.add(PollVote(answer_id=answer.id, ip=request.remote_addr))
    db.session.commit()
    return redirect(url_for('frontend.index'))


@bp.route('/reception/', methods=['GET', 'POST'])
def reception():
    if request.method == 'POST':
        r = Reception(subject=request.form.get('subject'),
                      fio=request.form.get('fio'),
                      address=request.form.get('address'),
                      phone=request.form.get('phone'),
                      email=request.form.get('email'),
                      body_text=request.form.get('body'))
        db.session.add(r)
        db.session.commit()
        return render_template('reception/success.html')
    return render_template('reception/form.html')


@bp.route('/sitemap.xml')
def sitemap():
    urls = ['/', '/news/', '/faq/', '/reception/']
    urls += [p.url for p in Page.query.all()]
    xml = '<?xml version="1.0" encoding="UTF-8"?>\n<urlset xmlns="https://www.sitemaps.org/schemas/sitemap/0.9">\n'
    base = request.url_root.rstrip('/')
    for u in urls:
        xml += f'<url><loc>{base}{u}</loc></url>\n'
    xml += '</urlset>'
    resp = make_response(xml)
    resp.headers['Content-Type'] = 'application/xml'
    return resp


@bp.route('/kiosk/')
def kiosk():
    """Страница сенсорного киоска (аналог /kiosk/kiosk.html оригинала)."""
    return render_template('kiosk.html')
