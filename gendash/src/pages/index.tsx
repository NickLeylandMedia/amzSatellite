/* Library Imports */
//React
import React, { useState, useEffect } from "react";

/* Stylesheet Imports */

/* Image Imports */

/* Component Imports */
import ASINBase from "@/components/ASINBase";
import AuditMenu from "@/components/AuditMenu";
import MainMenu from "@/components/MainMenu";

/* Module Imports */
import { useQuery } from "@tanstack/react-query";
import { getAsins, getFromDB } from "@/modules/api/asins";

/* Component Interfaces */
interface Props {}

/* Component */
const Home: React.FC<Props> = () => {
  /* State Variables */
  const [pageMode, setMode] = useState("main");
  const [asins, setAsins] = useState([]);
  /* End State Variables */

  /* Render Variables */
  /* End Render Variables */

  /* Functions */
  /* End Functions */

  /* Effects */
  //Shipment Query
  const asinsQuery = useQuery({
    queryKey: ["shipmentQuery"],
    queryFn: () => getAsins().then((data) => data),
    refetchInterval: 10000,
  });

  //Shipment Query
  const dbQuery = useQuery({
    queryKey: ["dbQuery"],
    queryFn: () => getFromDB().then((data) => data),
  });

  /* End Effects */

  /* Component Return Statement */
  if (pageMode === "asins") {
    return (
      <div className="Home page">
        {/* Header Start */}
        <header></header>
        {/* Header End */}
        {/* Content Start */}
        <div className="mainContent">
          <h1 className="text-3xl text-black text-center my-3">
            ASIN DATABASE
          </h1>
          <ASINBase
            asins={asinsQuery.data}
            actionData={dbQuery.data}
            dataIsLoading={dbQuery.isLoading}
            dataIsFetched={dbQuery.isFetched}
          />
        </div>
        {/* Content End */}
        {/* Footer Start */}
        {/* <Footer /> */}
        {/* Footer End */}
      </div>
    );
  }

  if (pageMode === "main") {
    return (
      <div className="Home page">
        {/* Header Start */}
        <header></header>
        {/* Header End */}
        {/* Content Start */}
        <div className="mainContent">
          <h1 className="text-3xl text-black text-center my-3">
            Amazon Satellite
          </h1>
          <MainMenu modeSetter={setMode} />
        </div>
        {/* Content End */}
        {/* Footer Start */}
        {/* <Footer /> */}
        {/* Footer End */}
      </div>
    );
  }

  if (pageMode === "audit") {
    return (
      <div className="Home page">
        {/* Header Start */}
        <header></header>
        {/* Header End */}
        {/* Content Start */}
        <div className="mainContent">
          <h1 className="text-3xl text-black text-center my-3">AUDITS</h1>
          <AuditMenu />
        </div>
        {/* Content End */}
        {/* Footer Start */}
        {/* <Footer /> */}
        {/* Footer End */}
      </div>
    );
  }
};

/* Export Statement */
export default Home;
