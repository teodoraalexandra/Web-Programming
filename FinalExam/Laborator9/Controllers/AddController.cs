using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;
using MySql.Data.MySqlClient;
using ExamTemplateAsp.Models;
using System.Web.UI.WebControls;
using System.Web.UI;
using PagedList.Mvc;
using PagedList;

namespace ExamTemplateAsp.Controllers
{
    public class AddController : Controller
    {
        public ActionResult Index()
        {
            return View ();
        }

        public ActionResult Add()
        {
            string topicId = Request.Params["stopicid"];
            string text = Request.Params["stext"];

            MySql.Data.MySqlClient.MySqlConnection conn;
            string myConnectionString;

            myConnectionString = "datasource=localhost;port=3306;username=root;password=;database=exam";

            try
            {
                conn = new MySql.Data.MySqlClient.MySqlConnection();
                conn.ConnectionString = myConnectionString;
                conn.Open();

                MySqlCommand cmd = new MySqlCommand();
                cmd.Connection = conn;
                cmd.CommandText = "insert into posts (user, topicid, text, date) values('" + Session["userName"] + "','" + topicId + "','" + text + "','2001-01-20 19:00:00')";
                cmd.ExecuteNonQuery();

            }
            catch (MySql.Data.MySqlClient.MySqlException ex)
            {
                Console.Write(ex.Message);
            }

            return RedirectToAction("Index", "Home");
        }
    }
}
