/* Library Imports */
// React
import React, { useState } from "react";

/* Stylesheet Imports */

/* Image Imports */

/* Component Imports */

/* Module Imports */
import { OTScope } from "@/modules/api/audits";

/* Component Interfaces */
interface Props {
  audits: any[];
}

/* Component */
const AuditMenu: React.FC<Props> = ({ audits }) => {
  /* State Variables */
  const [mode, setMode] = useState("main");
  const [target, setTarget] = useState<undefined | number>(undefined);
  /* End State Variables */

  /* Render Variables */
  let auditList: any = <p>No audits to display!</p>;
  let renderedSuccess: any = <p>No audit data to display!</p>;
  let renderedFailure: any = <p>No audit data to display!</p>;
  /* End Render Variables */

  /* Render Logic */
  if (audits && audits.length > 0) {
    auditList = audits.map((audit) => {
      return (
        <div
          key={audit.id}
          className="auditItem flex flex-row justify-evenly items-center content-center border border-black my-4 py-4"
        >
          <p>{audit.id}</p>
          <p>{new Date(audit.date_added).toLocaleString()}</p>
          <button
            className="bg-green-300 hover:bg-green-400 px-5 py-3"
            onClick={() => {
              setMode("otdetail");
              setTarget(audit.id);
            }}
          >
            VIEW
          </button>
        </div>
      );
    });
  }

  if (target) {
    const targData = audits.find((audit) => audit.id === target);
    const procData = JSON.parse(targData.info);
    // Remove duplicates from both arrays within procData based on price and sellerName
    procData[0] = procData[0].filter(
      (item: any, index: number, self: any) =>
        index ===
        self.findIndex(
          (t: any) =>
            t.offers[0].totalCost === item.offers[0].totalCost &&
            t.offers[0].sellerName === item.offers[0].sellerName
        )
    );

    if (procData[0].length > 0) {
      renderedSuccess = procData[0].map((item: any) => {
        const bhoPrice = item?.offers?.find(
          (item: any) => item?.sellerName === "Black Hall Outfitters"
        );

        if (!bhoPrice) {
          return null;
        }

        const renderedOffers = item.offers.map((offer: any) => {
          return (
            <div className="offer flex flex-col border border-black my-4 mx-3">
              <h4 className="text-xl text-black p-5">
                Seller Name: {offer.sellerName}
              </h4>
              <div className="ShippingSpeed flex flex-col w-[95%] mx-auto mb-3">
                <div className="flex flex-row justify-evenly">
                  <p className="">Min Handling:</p>
                  <p className="">{offer.minHandlingTime} Hours</p>
                </div>
                <div className="flex flex-row justify-evenly">
                  <p className="">Max Handling:</p>
                  <p className="">{offer.maxHandlingTime} Hours</p>
                </div>
              </div>

              {offer?.totalCost <= bhoPrice?.totalCost ||
              offer?.totalCost > bhoPrice?.totalCost ? (
                <div className="offerDetails px-5 py-2 m-2 bg-green-300">
                  <p className="">Shipping: ${offer.shippingCost}</p>
                  <p>Price: ${offer.totalCost}</p>
                </div>
              ) : (
                <div className="offerDetails px-5 py-2 m-2 bg-orange-400">
                  <p>Shipping: ${offer.shippingCost}</p>
                  <p>Price: ${offer.totalCost}</p>
                </div>
              )}
            </div>
          );
        });
        return (
          <div className="asin flex flex-col border border-black my-3 bg-gray-100">
            <h3 className="text-xl text-black p-5 ">ASIN: {item.asin}</h3>
            <div className="offerBox flex flex-row">{renderedOffers}</div>
          </div>
        );
      });
    }
  }
  /* End Render Logic */

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
      </div>
    );
  }

  if (mode === "otdetail") {
    return (
      <div className="AuditMenu w-full flex flex-col">
        <h1 className="text-2xl text-center">Old Town Audit Detail</h1>
        <div className="auditDetail">
          <div className="renderedSuccess flex flex-col">{renderedSuccess}</div>
          {/* <div className="renderedFailure">{renderedFailure}</div> */}
        </div>
      </div>
    );
  }

  if (mode === "oto") {
    return (
      <div className="AuditMenu w-full">
        <div className="otoMenu w-[90%] mx-auto">
          <button
            className="bg-yellow-500 hover:bg-yellow-600 py-4 w-full"
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
        <div className="otoDisplay w-[90%] mx-auto flex flex-col">
          {auditList}
        </div>
      </div>
    );
  }
};

/* Export Statement */
export default AuditMenu;
