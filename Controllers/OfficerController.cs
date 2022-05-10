using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;

namespace WEB.Controllers
{
    public class OfficerController : Controller
    {
        // GET: Officer
        public ActionResult Index()
        {
            return View();
        }

        // GET: Officer/Details/5
        public ActionResult CitizenDetails()
        {
            return View();
        }

        public ActionResult Citizens()
        {
            return View();
        }

        public ActionResult CompanyCreate()
        {
            return View();
        }

        public ActionResult Complaint()
        {
            return View();
        }

        public ActionResult ResponseDetail()
        {
            return View();
        }

    }
}
