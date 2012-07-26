from django.contrib import admin
from home.models import Project, BlogPost, NewsItem, ProjectCategory

admin.site.register(ProjectCategory)
admin.site.register(Project)
admin.site.register(BlogPost)
admin.site.register(NewsItem)