"""lektorganizator URL Configuration

The `urlpatterns` list routes URLs to views. For more information please see:
    https://docs.djangoproject.com/en/1.9/topics/http/urls/
Examples:
Function views
    1. Add an import:  from my_app import views
    2. Add a URL to urlpatterns:  url(r'^$', views.home, name='home')
Class-based views
    1. Add an import:  from other_app.views import Home
    2. Add a URL to urlpatterns:  url(r'^$', Home.as_view(), name='home')
Including another URLconf
    1. Import the include() function: from django.conf.urls import url, include
    2. Add a URL to urlpatterns:  url(r'^blog/', include('blog.urls'))
"""
from django.conf.urls import url
from django.contrib import admin

from app import views

urlpatterns = [
    url(r'^admin/', admin.site.urls),
    url(r"^$", views.index, name="index"),
    url(r"^lecturers/$", views.lecturer_list, name="lecturer-list"),
    url(r"^articles/$", views.article_list, name="article-list"),
    url(r"^articles/action/archive_all/$", views.archive_all, name="archive-all"),
    url(r"^archive/$", views.article_archive, name="article-archive"),
    url(r"^import/(?P<source>[^/]+)/suggestions/$", views.article_import_suggestions, name="article-import-suggestions"),
    url(r"^import/(?P<slug>[^/]+)$", views.article_import, name="article-import"),
    url(r"^articles/(?P<slug>[^/]+)/notify$", views.notify_lecturer, name="notify-lecturer"),
    url(r"^articles/(?P<slug>[^/]+)/attach/suggestions$", views.attach_lecturer_suggestions, name="attach-lecturer-suggestions"),
    url(r"^articles/(?P<slug>[^/]+)/attach/(?P<pk>\d+)$", views.attach_lecturer, name="attach-lecturer"),
    # url(r'^(?P<username>\w+)/blog/', include('foo.urls.blog')),
    # url(r'^articles/([0-9]{4})/$', views.year_archive, name='news-year-archive'),
]
