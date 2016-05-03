
from django.http import Http404
from django.shortcuts import render

from .models import Lecturer, Article 

from django.http import HttpResponseRedirect

import lektorganizator.settings
from .libre import LibreManager 

def index(request):
    return render(request, "lektorganizator/index.html")

def lecturer_list(request):
    lecturers = Lecturer.objects.all()
    return render(request, "lektorganizator/lecturer_list.html", {"lecturers": lecturers})

def article_list(request):
    articles = Article.objects.all()
    result = [] 
    for article in articles:
       if not article.archived:
           result.append(article)
    return render(request, "lektorganizator/article_list.html", {"articles": result})

def archive_all(request):
    articles = Article.objects.all()
    for article in articles: 
        if not article.archived:
            article.archived = True
            article.save()
    return HttpResponseRedirect(reverse('article-list'))


def article_archive(request):
    articles = Article.objects.all()
    return render(request, "lektorganizator/article_archive.html", {"articles": articles})

def article_import_suggestions(request, source):
    suggestions = [] 
    manager = LibreManager(settings.DOKUWIKI_USERNAME, settings.DOKUWIKI_PASSWORD)
    candidates = manager.getAllLinked(source) 
    for candidate in candidates:
        if not Article.objects.filter(slug=candidate.slug).exists():
            if candidate.getTitle() != "":
                suggestions.append(candidate)
    return render(request, "lektorganizator/article_import_suggestions.html", {"suggestions": suggestions, "source": source})

def article_import(request, slug):
    article = Article.objects.create(slug=slug)
    article.save()
    return HttpResponseRedirect(reverse('article-list'))

def notify_lecturer(request, slug):
    # TODO
    pass

def attach_lecturer_suggestions(request, slug):
    lecturers = Lecturer.objects.all()
    return render(request, "lektorganizator/attach_lecturer_suggestions.html", {"lecturers": lecturers, "slug": slug})

def attach_lecturer(request, slug, pk):
    article = Article.objects.get(slug=slug)
    article.lecturer = Lecturer.objects.get(pk=pk)
    article.save()
    return HttpResponseRedirect(reverse('article-list'))
