/* Library Imports */
//React
import React, { useState } from "react";

/* Stylesheet Imports */

/* Image Imports */

/* Component Imports */

/* Module Imports */
import { OTScope } from "@/modules/api/audits";

/* Component Interfaces */
interface Props {}

/* Component */
const AuditMenu: React.FC<Props> = () => {
  /* State Variables */
  const [mode, setMode] = useState("main");
  /* End State Variables */

  /* Render Variables */

  /* End Render Variables */

  /* Functions */
  /* End Functions */

  /* Effects */
  /* End Effects */

  /* Component Return Statement */
  if (mode === "main") {
    return (
      <div className="AuditMenu w-full">
        <div className="grid grid-cols-3 w-[95%] gap-4 mx-auto">
          <button className="bg-blue-400 hover:bg-blue-500 text-white cursor-pointer py-3">
            Audit Single ASIN
          </button>
          <button
            onClick={() => setMode("oto")}
            className="bg-orange-500 hover:bg-orange-600 text-white cursor-pointer"
          >
            Old Town Offerings
          </button>
          <button className="bg-green-500 hover:bg-green-600 text-white cursor-pointer">
            Full Catalog Viability
          </button>
        </div>
        ;
      </div>
    );
  }

  if (mode === "oto") {
    return (
      <div className="AuditMenu w-full">
        <div className="otoMenu w-[90%] mx-auto">
          <button
            className="bg-yellow-500 py-4 w-full"
            onClick={() =>
              OTScope([
                "B0CYXYM55N",
                "B0CYXZVRVC",
                "B08JHBYWT6",
                "B085JMYB2J",
                "B085JVR1ML",
                "B0CP8JQHBL",
                "B0CLTZR9DJ",
                "B0CLV1MQRM",
                "B0CP6CWCJC",
                "B0CP69P72B",
                "B0CPGB4CQG",
                "B0CPFYCQYP",
                "B0CPGDT6P9",
                "B0CLV1Y2RX",
                "B0CLTYX9DN",
                "B0CLV12DWT",
                "B0CLTZ5HCG",
                "B0CLTZ35RL",
                "B0CP6B7WF5",
                "B0CP6B9SMP",
                "B0CP6B2SFW",
                "B0CP6BY19L",
                "B085J1NB3X",
                "B085JKC34J",
                "B085J2CP53",
                "B085J1WQWC",
                "B085J1C25H",
                "B085J1F1WF",
                "B085JKB187",
                "B085JKC9JM",
                "B085JK7D4M",
                "B085JJZQ31",
                "B085JKC34X",
                "B085J29JG1",
                "B08JHC1KFH",
                "B085JQXWCM",
                "B085K32J4R",
              ])
            }
          >
            SCOPE OFFERINGS
          </button>
        </div>
        <div className="otoDisplay"></div>
      </div>
    );
  }
};

/* Export Statement */
export default AuditMenu;
