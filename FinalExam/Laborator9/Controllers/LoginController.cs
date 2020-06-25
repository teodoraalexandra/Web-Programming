using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;
using ExamTemplateAsp.Models;
using MySql.Data.MySqlClient;

namespace ExamTemplateAsp.Controllers
{
    public class LoginController : Controller
    {
        public ActionResult Index()
        {
            return View ();
        }

        [HttpPost]
        public ActionResult Authorize(ExamTemplateAsp.Models.Post user)
        {
            Console.WriteLine(user.Username);
            MySql.Data.MySqlClient.MySqlConnection conn;
            string myConnectionString;
            string result = "Wrong username and password";

            myConnectionString = "datasource=localhost;port=3306;username=root;password=;database=exam";

            try
            {
                conn = new MySql.Data.MySqlClient.MySqlConnection();
                conn.ConnectionString = myConnectionString;
                conn.Open();
                MySqlCommand cmd = new MySqlCommand();
                cmd.Connection = conn;
                cmd.CommandText = "select * from posts where user='" + user.Username + "'";
                MySqlDataReader myreader = cmd.ExecuteReader();
           
                while (myreader.Read())
                {
                    
                        result = "Good authentification";
                        //The authentification is good
                        cmd.CommandText = "select id from posts where user='" + user.Username + "'";
                        int id = 0;
                        while (myreader.Read())
                        {
                            id = myreader.GetInt32("id");
                        }
                        Session["userId"] = id;
                        Session["userName"] = user.Username;
                        return RedirectToAction("Index", "Home");
                    
                }
                myreader.Close();
            }
            catch (MySql.Data.MySqlClient.MySqlException ex)
            {
                Console.Write(ex.Message);
            }

            return View("Index", user);
        }

        public ActionResult LogOut()
        {
            int userId = (int)Session["userId"];
            Session.Abandon();
            return RedirectToAction("Index", "Login");
        }
    }
}
