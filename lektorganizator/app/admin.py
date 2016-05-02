from django.contrib.auth import get_user_model

from django.contrib import admin

from .models import (
    Article,
    Lecturer
)


admin.site.register(Article)
admin.site.register(Lecturer)
