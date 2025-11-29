from django.shortcuts import render

# Create your views here.

from django.shortcuts import render, get_object_or_404
from .models import Product, Category

def index(request):
    products = Product.objects.all()
    return render(request, "index.html", {"products": products})

def category_view(request, category_id):
    category = get_object_or_404(Category, id=category_id)
    products = Product.objects.filter(category=category)
    return render(request, "category.html", {
        "category": category,
        "products": products,
    })

def login_view(request):
    return render(request, "login.html")

def signup_view(request):
    return render(request, "signup.html")


