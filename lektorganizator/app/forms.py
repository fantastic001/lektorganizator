
from django import forms
from .models import Article, Lecturer 

class ArticleForm(forms.ModelForm):
    class Meta:
        model = Article 
        fields = []

class ContactForm(forms.Form):
    subject = forms.CharField(max_length=50, label="Naslov")
    to = forms.EmailField(label="Za")
    reply = forms.EmailField(label="Od")
    message = forms.CharField(max_length=1024, widget=forms.Textarea, label="Poruka")

class LoginForm(forms.Form):
    username = forms.CharField(max_length=20)
    password = forms.CharField(widget=forms.PasswordInput, max_length=256)
