from django.db import models

class Lecturer(models.Model):
    is_female = models.BooleanField()
    first_name = models.CharField(max_length=20)
    last_name = models.CharField(max_length=40)
    email = models.EmailField()

    def __str__(self):
        return "%s %s" % (self.first_name, self.last_name)


class Article(models.Model):
    slug = models.SlugField(allow_unicode=True) 
    archived = models.BooleanField(default=False) 
    lecturer = models.ForeignKey(Lecturer, blank=True, null=True)
    notified = models.BooleanField(default=False) 

    def __str__(self):
        return self.slug

class Source(models.Model):
    slug = models.CharField(max_length=60) 
    title = models.CharField(max_length=60)
    description = models.CharField(max_length=120)

    def __str__(self):
        return self.title
