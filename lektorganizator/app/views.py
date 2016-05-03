
from django.http import Http404
from django.shortcuts import render
from django.core.urlresolvers import reverse
from .models import Lecturer, Article 

from django.http import HttpResponseRedirect

from lektorganizator import wiki
from .libre import LibreManager 

from django.core.mail import send_mail

from .forms import *

from lektorganizator import email_settings

from django.contrib.auth import authenticate, login, logout
from django.contrib.auth.decorators import login_required

def index(request):
    return render(request, "lektorganizator/index.html")

def user_login(request):
    if request.method == "POST":
        username = request.POST['username']
        password = request.POST['password']
        user = authenticate(username=username, password=password)
        if user is not None:
            if user.is_active:
                login(request, user)
                return HttpResponseRedirect(reverse('article-list'))
            else: 
                return HttpResponseRedirect(reverse('login'))
        else:
            return HttpResponseRedirect(reverse('login'))
    else:
        form = LoginForm()
        return render(request, "lektorganizator/login.html", {"form": form})

def user_logout(request):
    logout(request)
    return HttpResponseRedirect(reverse('login'))

@login_required
def lecturer_list(request):
    lecturers = Lecturer.objects.all()
    return render(request, "lektorganizator/lecturer_list.html", {"lecturers": lecturers})

@login_required
def article_list(request):
    articles = Article.objects.all()
    result = [] 
    for article in articles:
       if not article.archived:
           result.append(article)
    return render(request, "lektorganizator/article_list.html", {"articles": result})

@login_required
def archive_all(request):
    articles = Article.objects.all()
    for article in articles: 
        if not article.archived:
            article.archived = True
            article.save()
    return HttpResponseRedirect(reverse('article-list'))

@login_required
def article_archive(request):
    articles = Article.objects.all()
    return render(request, "lektorganizator/article_archive.html", {"articles": articles})

@login_required
def article_import_suggestions(request, source):
    suggestions = [] 
    manager = LibreManager(wiki.DOKUWIKI_USERNAME, wiki.DOKUWIKI_PASSWORD)
    candidates = manager.getAllLinked(source) 
    for candidate in candidates:
        if not Article.objects.filter(slug=candidate.slug).exists():
            if candidate.getTitle() != "":
                suggestions.append(candidate)
    return render(request, "lektorganizator/article_import_suggestions.html", {"suggestions": suggestions, "source": source})

@login_required
def article_import(request, slug):
    article = Article.objects.create(slug=slug)
    article.save()
    return HttpResponseRedirect(reverse('article-list'))

@login_required
def notify_lecturer(request, slug):
    article = Article.objects.get(slug=slug) 
    manager = LibreManager(wiki.DOKUWIKI_USERNAME, wiki.DOKUWIKI_PASSWORD)
    text = manager.getPage(article.slug)
    if request.method == "GET":
        form = ContactForm({
            "subject": "Lektorisanje",
            "message": """
Pozdrav!

Za ovaj broj tekst za lektorisanje je %s na %s

Rok: 

Molim te javi mi ako ti rok ne odgovara.
            """ % (text.getTitle(), text.url),
            "to": article.lecturer.email,
            "reply": email_settings.EMAIL_SENDER
        })
        return render(request, "lektorganizator/notify_lecturer.html", {"form": form, "slug": slug})
    else:
        form = ContactForm(request.POST)
        if form.is_valid():
            send_mail(
                form.cleaned_data["subject"], 
                form.cleaned_data["message"], 
                form.cleaned_data["reply"],
                [form.cleaned_data["to"]], 
                fail_silently=False)
            article.notified = True
            article.save()
            return HttpResponseRedirect(reverse('article-list'))
        else:
            return render(request, "lektorganizator/notify_lecturer.html", {"form": form, "slug": slug})

@login_required
def attach_lecturer_suggestions(request, slug):
    lecturers = Lecturer.objects.all()
    return render(request, "lektorganizator/attach_lecturer_suggestions.html", {"lecturers": lecturers, "slug": slug})

@login_required
def attach_lecturer(request, slug, pk):
    article = Article.objects.get(slug=slug)
    article.lecturer = Lecturer.objects.get(pk=pk)
    article.save()
    return HttpResponseRedirect(reverse('article-list'))
