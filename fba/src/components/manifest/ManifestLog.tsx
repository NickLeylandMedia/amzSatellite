/* Library Imports */
//React
import React from "react";

/* Stylesheet Imports */

/* Image Imports */

/* Component Imports */

/* Module Imports */

/* Component Interfaces */
interface Props {
  shipments: any[];
}

/* Component */
const ManifestLog: React.FC<Props> = ({ shipments }) => {
  /* State Variables */
  /* End State Variables */

  /* Render Variables */
  let renderedShipments: any = "No Shipments Found";
  /* End Render Variables */

  /* Render Logic */
  if (shipments && shipments.length > 0) {
    renderedShipments = shipments.map((shipment) => {
      return (
        <div
          key={shipment.id}
          className="shipmentCard border border-black p-5 m-5"
        >
          <h3 className="text-black text-2xl">Shipment {shipment.id}</h3>
          <p className="text-black text-lg">Name: {shipment.name}</p>
          <p className="text-black text-lg">Date: {shipment.date}</p>
        </div>
      );
    });
  }
  /* End Render Logic */

  /* Functions */
  /* End Functions */

  /* Effects */
  /* End Effects */

  /* Component Return Statement */
  return (
    <div className="ManifestLog w-[95%] mx-auto my-5">
      <h1 className="text-black text-center text-4xl">FBA Shipment Manifest</h1>
      <div className="manifestActions mx-auto w-[50%] flex flex-row justify-center my-4">
        <button className="py-3 px-8 bg-green-400 hover:bg-green-700">
          Add A Shipment
        </button>
      </div>
      <div className="shipmentLog grid grid-cols-3 w-full mx-auto my-5">
        {renderedShipments}
      </div>
    </div>
  );
};

/* Export Statement */
export default ManifestLog;
