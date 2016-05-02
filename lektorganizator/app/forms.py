
from django.forms import Form, ModelForm
from .models import Article, Lecturer 

class ArticleForm(ModelForm):
    class Meta:
        model = Article 
        fields = []
