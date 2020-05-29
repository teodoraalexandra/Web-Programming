using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;
using MySql.Data.MySqlClient;
using Laborator9.Models;
using System.Web.UI.WebControls;
using System.Web.UI;

namespace Laborator9.Controllers
{

    public class CartController : Controller
    {
        public ActionResult Index()
        {
            return View ();
        }

        public string GetAllProducts()
        {
            MySql.Data.MySqlClient.MySqlConnection conn;
            string myConnectionString;

            myConnectionString = "datasource=localhost;port=3306;username=root;password=;database=ecommerce";

            List<Cart> products = new List<Cart>();

            try
            {
                conn = new MySql.Data.MySqlClient.MySqlConnection();
                conn.ConnectionString = myConnectionString;
                conn.Open();

                MySqlCommand cmd = new MySqlCommand();
                cmd.Connection = conn;
                cmd.CommandText = "select * from cart where username='" + Session["userName"] + "'";
                MySqlDataReader myreader = cmd.ExecuteReader();

                while (myreader.Read())
                {
                    Cart product = new Cart();
                    product.Id = myreader.GetInt32("idcart");
                    product.Name = myreader.GetString("product_name");
                    product.Price = myreader.GetInt32("product_price");
                    product.Quantity = myreader.GetInt32("qty");
                    product.Total = myreader.GetInt32("total_price");
                    products.Add(product);
                }
                myreader.Close();
            }
            catch (MySql.Data.MySqlClient.MySqlException ex)
            {
                Console.Write(ex.Message);
            }

            ViewData["productList"] = products;

            string result = "<script>function deleteSubmit(form) { if (confirm('Are you sure you want to delete this product ? ')) { form.submit(); return true; } else { return false; } }</script>";
            result += "<b> Name&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspPrice&nbsp&nbsp&nbsp&nbsp&nbsp&nbspQuantity&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspTotal </b><br/><br/>";
            result += "<hr></hr>";


            foreach (Cart prod in products)
            {
                result += "<br/><form method='post' >" +
                    "<input type='hidden' id='pname' name='product_name' value='" + prod.Name + "'> " + prod.Name + "</input>" +
                    "&nbsp&nbsp<input type='hidden' id='pprice' name='product_price' value='" + prod.Price + "'>" + prod.Price +

                    "&nbsp&nbsp<input type='text' id='input' name='qty' value='" + prod.Quantity + "'>" + 

                    "&nbsp&nbsp<input type='hidden' id='ptotal' name='total_price' value='" + prod.Total + "'>" + prod.Total +
                    "&nbsp&nbsp<input type='submit' class='button' name='delete' value='Delete product' onClick=' return deleteSubmit(this.form); ' formaction='/Cart/DeleteProductFromCart'/>" +
                    "&nbsp&nbsp<input type='submit' class='button' name='update' value='Update product' formaction='/Cart/UpdateProductFromCart'/>" +
                    
                    "</form><br/>";
                result += "<hr></hr>";
            }

            return result;
        }

        public ActionResult UpdateProductFromCart()
        {
            string product_name = Request.Params["product_name"];
            string product_price = Request.Params["product_price"];
            string qty = Request.Params["qty"];
            int total_price = Int32.Parse(Request.Params["total_price"]);

            try
            {
                total_price = Int32.Parse(product_price) * Int32.Parse(qty);
            } catch 
            {
                TempData["error"] = "Quantity must be a number!";
                return RedirectToAction("Index", "Cart");
            }

            if (Int32.Parse(qty) < 1)
            {
                TempData["error"] = "Quantity must be a positive number!";
                return RedirectToAction("Index", "Cart");
            }

            MySql.Data.MySqlClient.MySqlConnection conn;
            string myConnectionString;

            myConnectionString = "datasource=localhost;port=3306;username=root;password=;database=ecommerce";

            try
            {
                conn = new MySql.Data.MySqlClient.MySqlConnection();
                conn.ConnectionString = myConnectionString;
                conn.Open();

                MySqlCommand cmd = new MySqlCommand();
                cmd.Connection = conn;
                cmd.CommandText = "update cart set qty='" + qty + "', total_price='" + total_price + "' where product_name='" + product_name + "' and username='" + Session["userName"] + "'";
                cmd.ExecuteNonQuery();

            }
            catch (MySql.Data.MySqlClient.MySqlException ex)
            {
                Console.Write(ex.Message);
            }

            return RedirectToAction("Index", "Cart");
        }

        public ActionResult DeleteProductFromCart()
        {
            string product_name = Request.Params["product_name"];

            MySql.Data.MySqlClient.MySqlConnection conn;
            string myConnectionString;

            myConnectionString = "datasource=localhost;port=3306;username=root;password=;database=ecommerce";

            try
            {
                conn = new MySql.Data.MySqlClient.MySqlConnection();
                conn.ConnectionString = myConnectionString;
                conn.Open();

                MySqlCommand cmd = new MySqlCommand();
                cmd.Connection = conn;
                cmd.CommandText = "delete from cart where product_name='" + product_name + "' and username='" + Session["userName"] + "'";
                cmd.ExecuteNonQuery();

            }
            catch (MySql.Data.MySqlClient.MySqlException ex)
            {
                Console.Write(ex.Message);
            }

            return RedirectToAction("Index", "Cart");
        }

        public ActionResult GoBack()
        {
            return RedirectToAction("Index", "Home");
        }
    }
}
