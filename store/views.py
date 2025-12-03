from django.shortcuts import render, get_object_or_404, redirect
from django.contrib import messages
from django.contrib.auth import login, logout
from django.contrib.auth.models import User
from django.contrib.auth.decorators import user_passes_test, login_required
from django.db.models import Sum

from .models import Product, Category, CheckoutItem, Customer
from decimal import Decimal


def index(request):
    products = Product.objects.all()
    return render(request, "store/index.html", {"products": products})


def category_view(request, category_id):
    category = get_object_or_404(Category, id=category_id)
    products = Product.objects.filter(category=category)
    return render(request, "store/category.html", {
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
            return render(request, "account/signup.html")

        if User.objects.filter(username=email).exists():
            messages.error(request, "An account with this email already exists.")
            return render(request, "account/signup.html")

        user = User.objects.create_user(
            username=email,
            email=email,
            password=password1,
            first_name=first_name,
            last_name=last_name,
        )

        Customer.objects.create(
            first_name=first_name,
            last_name=last_name,
            email=email,
            password="(handled by Django)",
        )

        login(request, user)
        return redirect("index")

    return render(request, "account/signup.html")


def logout_view(request):
    logout(request)
    return redirect("index")

def checkout_view(request):
    customer = Customer.objects.first()
    checkout_items = CheckoutItem.objects.filter(customer=customer) if customer else []

    subtotal = sum((item.total_price for item in checkout_items), Decimal("0.00"))
    delivery = Decimal("0.00")
    total = subtotal + delivery

    return render(request, "store/checkout.html", {
        "customer": customer,
        "checkout_items": checkout_items,
        "subtotal": subtotal,
        "delivery": delivery,
        "total": total,
    })

def checkout_customer_view(request):
    return render(request, "store/checkout_customer.html")

from decimal import Decimal
from django.shortcuts import redirect

def checkout_view(request):
    customer = Customer.objects.first()
    checkout_items = CheckoutItem.objects.filter(customer=customer) if customer else []

    subtotal = sum((item.total_price for item in checkout_items), Decimal("0.00"))
    delivery = Decimal("0.00")
    total = subtotal + delivery

    return render(request, "store/checkout.html", {
        "customer": customer,
        "checkout_items": checkout_items,
        "subtotal": subtotal,
        "delivery": delivery,
        "total": total,
    })

@login_required
def checkout_payment_view(request):
    customer = Customer.objects.filter(email=request.user.email).first()

    checkout_items = CheckoutItem.objects.filter(customer=customer) if customer else []
    subtotal = sum((item.total_price for item in checkout_items), Decimal("0.00"))
    delivery = Decimal("0.00")
    total = subtotal + delivery

    if request.method == "POST":
        #handle payment + save address

        return redirect("checkout_complete")

    return render(request, "store/checkout_payment.html", {
        "customer": customer,
        "subtotal": subtotal,
        "delivery": delivery,
        "total": total,
    })



def checkout_complete_view(request):
    return render(request, "store/checkout_complete.html")



def staff_check(user):
    return user.is_staff


@user_passes_test(staff_check)
def staff_dashboard(request):
    product_count = Product.objects.count()
    customer_count = Customer.objects.count()
    checkout_value = CheckoutItem.objects.aggregate(
        total=Sum("total_price")
    )["total"] or 0

    context = {
        "product_count": product_count,
        "customer_count": customer_count,
        "checkout_value": checkout_value,
    }
    return render(request, "staff/dashboard.html", context)


@login_required
def customer_dashboard(request):
    return render(request, "account/dashboard.html")


@login_required
def dashboard_router(request):
    if request.user.is_staff:
        return redirect("staff_dashboard")
    return redirect("customer_dashboard")


def contact_us(request):
    if request.method == "POST":
        return render(request, "store/contact_us.html", {"sent": True})

    return render(request, "store/contact_us.html")