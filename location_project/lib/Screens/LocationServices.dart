import 'package:flutter/material.dart';
import 'package:geolocator/geolocator.dart';

class LocationServices extends StatefulWidget {
  const LocationServices({super.key});

  @override
  State<LocationServices> createState() => _LocationServicesState();
}

class _LocationServicesState extends State<LocationServices> {
  String locationMessage = "Location not yet grabbed";

  Future<void> getCurrentLocation() async {
    try {
      // Check if location services are enabled
      bool serviceEnabled = await Geolocator.isLocationServiceEnabled();
      if (!serviceEnabled) {
        setState(() {
          locationMessage = "Location services are disabled";
        });
        return;
      }

      // Check for location permission
      LocationPermission permission = await Geolocator.checkPermission();
      if (permission == LocationPermission.denied) {
        permission = await Geolocator.requestPermission();
        if (permission == LocationPermission.denied ||
            permission == LocationPermission.deniedForever) {
          setState(() {
            locationMessage = "Location permission denied permanently";
          });
          return;
        }
      }

      // Get current position
      Position currentPosition = await Geolocator.getCurrentPosition(
          desiredAccuracy: LocationAccuracy.best);

      setState(() {
        locationMessage =
        "Latitude: ${currentPosition.latitude},\nLongitude: ${currentPosition.longitude}";
      });
    } catch (e) {
      setState(() {
        locationMessage = "Error getting location: $e";
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text("Geolocator"),
        centerTitle: true,
      ),
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            ElevatedButton(
              onPressed: getCurrentLocation,
              child: const Text("Grab Location"),
            ),
            const SizedBox(height: 20),
            Text(
              locationMessage,
              textAlign: TextAlign.center,
              style: const TextStyle(fontSize: 16),
            ),
          ],
        ),
      ),
    );
  }
}
