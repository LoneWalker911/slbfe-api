﻿using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Mvc;

namespace WEB.Controllers
{
    public class CitizenController : Controller
    {
        // GET: Citizen
        public ActionResult Index()
        {
            return View();
        }

        public ActionResult Qualification()
        {
            return View();
        }
                
    }
}