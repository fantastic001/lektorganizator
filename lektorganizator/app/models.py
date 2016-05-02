from django.db import models

class Lecturer(models.Model):
    is_female = models.BooleanField()
    first_name = models.CharField(max_length=20)
    last_name = models.CharField(max_length=40)
    email = models.EmailField()


class Article(models.Model):
    slug = models.SlugField(allow_unicode=True) 
    archived = models.BooleanField(default=False) 
    lecturer = models.ForeignKey(Lecturer, blank=True, null=True)
    notified = models.BooleanField(default=False) 
