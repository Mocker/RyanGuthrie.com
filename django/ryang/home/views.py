# Create your views here.
from django.http import HttpResponse
from django.shortcuts import render_to_response
from django.core.mail import send_mail
from django.core import serializers
from home.models import Project, ProjectCategory

import smtplib, sys, json

def index(request):
    return render_to_response('default.html', {'latest_poll_list': "none"})

def contact(request):
	return render_to_response('contact.html',{})


def send_email(request):
	msg = "Email sent"
	postvalues = request.POST
	fullmsg = "Form details: \n"
	for param in postvalues:
		fullmsg += param+": "+str(postvalues[param])+"\n"
	#poststring = str(postvalues)
	#return HttpResponse(fullmsg)
	try:
		send_mail('Message from RyanGuthrie.com', "You got an email from RyanGuthrie.com: \n"+fullmsg, 'ryang@ryanguthrie.com',
			['ryan@ryanguthrie.com'], fail_silently=False)
	except:
		z = str(sys.exc_info()[1])
		msg = "SMTPException when sending email \n"+z
	#return render_to_resposne("contact.html",{'error_email':msg})
	return HttpResponse(msg)

def send_quote(request):
	msg = "Quotes sent"
	postvalues = request.POST
	fullmsg = "Form details: \n"
	for param in postvalues:
		fullmsg += param+": "+str(postvalues[param])+"\n"
	try:
		send_mail('Quote from RyanGuthrie.com', "You got an quote from RyanGuthrie.com: \n"+fullmsg, 'ryang@ryanguthrie.com',
			['ryan@ryanguthrie.com'], fail_silently=False)
	except:
		z = str(sys.exc_info()[1])
		msg = "SMTPException when sending email \n"+z
	return HttpResponse(msg)

def about(request):
	return render_to_response('about.html',{})

def resume(request):
	return render_to_response('resume.html',{})

def services(request):
	return render_to_response('services.html',{})

#portfolio and project views have been combined into showcase
def portfolio(request):
	print "portfolio"

def projects(request):
	print "projects"

def showcase(request):
	
	projects = dict()
	
	projectsAll = Project.objects.all()
	for proj in projectsAll:
		projects[proj.strID] = {
			"title":proj.title,
			"strID": proj.strID,
			"thumbSrc": proj.thumbSrc,
			"description": proj.description,
			"gitURL": proj.gitURL,
			"webURL": proj.webURL,
		}
	projectJSON = json.dumps(projects)
	#projectJSON = serializers.serialize("json",projects)
	projectCats = ProjectCategory.objects.all()
	projectDB = list()
	for cat in projectCats:
		catObj = {
			"title": cat.title,
			"projects" : Project.objects.filter(project_category=cat),
		}
		projectDB.append(catObj)
		#print cat.title + "-" + catObj.projects

	return render_to_response('showcase.html',{
	'projectDB': projectDB,
	'projects':projects,
	'projectJSON':projectJSON,
	})


def projectData(request):
	msg = '';

	return HttpResponse(msg)