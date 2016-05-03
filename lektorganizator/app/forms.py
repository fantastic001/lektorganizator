
from django.forms import Form, ModelForm, CharField, EmailField, Textarea
from .models import Article, Lecturer 

class ArticleForm(ModelForm):
    class Meta:
        model = Article 
        fields = []

class ContactForm(Form):
    subject = CharField(max_length=50, label="Naslov")
    to = EmailField(label="Za")
    reply = EmailField(label="Od")
    message = CharField(max_length=1024, widget=Textarea, label="Poruka")
