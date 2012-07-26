from django.db import models

# Create your models here.
class ProjectCategory(models.Model):
	title = models.CharField(max_length=120)
	def __unicode__(self):
	    return self.title

class Project(models.Model):
	project_category = models.ForeignKey(ProjectCategory)
	title = models.CharField(max_length=120)
	strID = models.CharField(max_length=30)
	thumbSrc = models.CharField(max_length=150)
	description = models.TextField()
	gitURL = models.CharField(max_length=120)
	webURL = models.CharField(max_length=120)
	def __unicode__(self):
	    return self.title

class BlogPost(models.Model):
	title = models.CharField(max_length=120)
	def __unicode__(self):
		return self.title

class NewsItem(models.Model):
	title = models.CharField(max_length=120)
	def __unicode__(self):
		return self.title