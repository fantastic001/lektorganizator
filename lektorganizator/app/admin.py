from django.contrib.auth import get_user_model

from django.contrib import admin

from .models import (
    Article,
    Lecturer,
    Source,
)


admin.site.register(Article)
admin.site.register(Lecturer)
admin.site.register(Source)
