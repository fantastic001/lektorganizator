# -*- coding: utf-8 -*-
# Generated by Django 1.9.5 on 2016-05-03 17:56
from __future__ import unicode_literals

from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('app', '0001_initial'),
    ]

    operations = [
        migrations.CreateModel(
            name='Source',
            fields=[
                ('id', models.AutoField(auto_created=True, primary_key=True, serialize=False, verbose_name='ID')),
                ('slug', models.SlugField(allow_unicode=True)),
                ('title', models.CharField(max_length=60)),
                ('description', models.CharField(max_length=120)),
            ],
        ),
    ]
