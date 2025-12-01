from django.shortcuts import render, get_object_or_404, redirect
from django.contrib import messages
from django.contrib.auth import login, logout
from django.contrib.auth.models import User

from .models import Product, Category, CartItem, Customer


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


def signup_view(request):
    if request.method == "POST":
        title = request.POST.get("title")
        first_name = request.POST.get("first_name")
        last_name = request.POST.get("last_name")
        email = request.POST.get("email")
        phone = request.POST.get("phone")
        password1 = request.POST.get("password1")
        password2 = request.POST.get("password2")

        if password1 != password2:
            messages.error(request, "Passwords do not match.")
            return render(request, "signup.html")

        if User.objects.filter(username=email).exists():
            messages.error(request, "An account with this email already exists.")
            return render(request, "signup.html")

        # create Django user account (email as username)
        user = User.objects.create_user(
            username=email,
            email=email,
            password=password1,
            first_name=first_name,
            last_name=last_name,
        )

        # optional
        Customer.objects.create(
            first_name=first_name,
            last_name=last_name,
            email=email,
            password="(handled by Django)",
        )

        login(request, user)
        return redirect("index")

    return render(request, "signup.html")

def logout_view(request):
    logout(request)
    return redirect('index')

def cart_view(request):
    customer = Customer.objects.first()
    if customer is None:
        cart_items = []
    else:
        cart_items = CartItem.objects.filter(customer=customer)
    return render(request, "cart.html", {
        "customer": customer,
        "cart_items": cart_items,
    })
