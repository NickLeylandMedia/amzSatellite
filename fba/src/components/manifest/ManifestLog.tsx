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
  modeSetter: (string: string) => void;
  targeter: (any: any) => void;
}

/* Component */
const ManifestLog: React.FC<Props> = ({ shipments, modeSetter, targeter }) => {
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
          <p className="text-black text-lg">Summary: {shipment.summary}</p>
          <button
            onClick={(e) => targeter(shipment.id)}
            className="my-2 py-2 px-7 bg-blue-500 hover:bg-blue-700 text-white"
          >
            VIEW
          </button>
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
        <button
          className="py-3 px-8 bg-green-400 hover:bg-green-700"
          onClick={(e) => modeSetter("form")}
        >
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
